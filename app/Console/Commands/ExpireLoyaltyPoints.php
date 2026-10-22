<?php

namespace App\Console\Commands;

use App\Models\LoyaltyAccount;
use App\Models\LoyaltyTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpireLoyaltyPoints extends Command
{
    protected $signature = 'loyalty:expire-points';

    protected $description = 'Expire les lots de points de fidélité arrivés à échéance';

    public function handle(): int
    {
        $now = now();
        $userIds = LoyaltyTransaction::query()
            ->whereIn('type', ['earn', 'referral', 'adjust'])
            ->where('points', '>', 0)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $now)
            ->distinct()
            ->pluck('user_id');
        $expiredTotal = 0;

        foreach ($userIds as $userId) {
            $expiredTotal += DB::transaction(function () use ($userId, $now) {
                $transactions = LoyaltyTransaction::where('user_id', $userId)
                    ->orderBy('created_at')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();
                $alreadyExpired = $transactions
                    ->where('type', 'expire')
                    ->whereNotNull('source_transaction_id')
                    ->pluck('source_transaction_id')
                    ->map(fn ($id) => (int) $id)
                    ->all();
                $alreadyExpired = array_fill_keys($alreadyExpired, true);
                $events = [];

                foreach ($transactions as $transaction) {
                    if (in_array($transaction->type, ['earn', 'referral', 'adjust'], true) && $transaction->points > 0) {
                        $events[] = [
                            'at' => $transaction->created_at,
                            'priority' => 0,
                            'kind' => 'grant',
                            'transaction' => $transaction,
                        ];

                        if ($transaction->expires_at && $transaction->expires_at->lessThanOrEqualTo($now)) {
                            $events[] = [
                                'at' => $transaction->expires_at,
                                'priority' => 1,
                                'kind' => 'expire',
                                'transaction' => $transaction,
                            ];
                        }
                    } elseif ($transaction->type === 'redeem') {
                        $events[] = [
                            'at' => $transaction->created_at,
                            'priority' => 2,
                            'kind' => 'redeem',
                            'transaction' => $transaction,
                        ];
                    }
                }

                usort($events, function (array $left, array $right) {
                    $timeComparison = $left['at']->getTimestamp() <=> $right['at']->getTimestamp();
                    if ($timeComparison !== 0) {
                        return $timeComparison;
                    }

                    $priorityComparison = $left['priority'] <=> $right['priority'];
                    return $priorityComparison !== 0
                        ? $priorityComparison
                        : $left['transaction']->id <=> $right['transaction']->id;
                });

                $lots = [];
                $expiredForUser = 0;

                foreach ($events as $event) {
                    $transaction = $event['transaction'];

                    if ($event['kind'] === 'grant') {
                        $lots[$transaction->id] = (int) $transaction->points;
                        continue;
                    }

                    if ($event['kind'] === 'redeem') {
                        $toRedeem = abs((int) $transaction->points);
                        foreach ($lots as &$remaining) {
                            $consumed = min($remaining, $toRedeem);
                            $remaining -= $consumed;
                            $toRedeem -= $consumed;
                            if ($toRedeem === 0) {
                                break;
                            }
                        }
                        unset($remaining);
                        continue;
                    }

                    $remaining = $lots[$transaction->id] ?? 0;
                    if (isset($alreadyExpired[$transaction->id])) {
                        $lots[$transaction->id] = 0;
                        continue;
                    }

                    $account = LoyaltyAccount::firstOrCreate(
                        ['user_id' => $userId],
                        ['balance_points' => 0]
                    );
                    $account = LoyaltyAccount::where('user_id', $userId)->lockForUpdate()->firstOrFail();
                    $expired = min($remaining, max(0, (int) $account->balance_points));

                    if ($expired > 0) {
                        $account->decrement('balance_points', $expired);
                    }

                    LoyaltyTransaction::create([
                        'user_id' => $userId,
                        'type' => 'expire',
                        'points' => $expired,
                        'description' => 'Expiration des points de la transaction #'.$transaction->id,
                        'source_transaction_id' => $transaction->id,
                    ]);
                    $expiredForUser += $expired;

                    $lots[$transaction->id] = 0;
                }

                return $expiredForUser;
            });
        }

        $this->info("Points expirés: {$expiredTotal}.");

        return self::SUCCESS;
    }
}
