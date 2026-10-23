<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use App\Models\Claim;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyTransaction;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Referral;
use App\Models\ReferralCode;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/**
 * Données de démonstration.
 * Idempotent : on peut le relancer sans créer de doublons.
 * Lancement : php artisan db:seed --class=DemoSeeder
 */
class DemoSeeder extends Seeder
{
    private const PASSWORD = 'Password123!';

    public function run(): void
    {
        // Prérequis, créés seulement s'ils n'existent pas déjà
        if (Role::count() === 0) {
            $this->call(RolesAndSettingsSeeder::class);
        }
        if (MenuItem::count() === 0) {
            $this->call(MassDataSeeder::class);
        }

        Model::unguarded(function () {
            $this->settings();
            $users = $this->users();
            $orders = $this->orders($users);
            $this->referrals($users);
            $this->claims($users, $orders);
        });

        $this->command->info('Démo prête. Mot de passe de tous les comptes : ' . self::PASSWORD);
    }

    // Ne garde que les colonnes qui existent vraiment dans la table
    private function cols(Model $model, array $data): array
    {
        return array_intersect_key($data, array_flip(Schema::getColumnListing($model->getTable())));
    }

    // Réglages utilisés par la page d'accueil (liens légaux, version des cookies)
    private function settings(): void
    {
        $values = [
            'privacy_policy_url'    => 'https://example.com/confidentialite',
            'terms_of_service_url'  => 'https://example.com/conditions',
            'cookie_policy_version' => '1.0',
        ];

        foreach ($values as $key => $value) {
            AppSetting::firstOrCreate(['setting_key' => $key], ['setting_value' => $value]);
        }
    }

    private function users(): array
    {
        $hash = Hash::make(self::PASSWORD);

        // rôle, prénom, nom, email, code de parrainage, actif, points de fidélité
        $people = [
            ['admin',    'Admin',    'Démo',      'admin@demo.test',    null,       true,  null],
            ['manager',  'Gérant',   'Démo',      'gerant@demo.test',   null,       true,  null],
            ['employee', 'Employé',  'Démo',      'employe@demo.test',  null,       true,  null],
            ['student',  'Amina',    'Nkoulou',   'amina@demo.test',    'AMINA026', true,  12],
            ['student',  'Junior',   'Essomba',   'junior@demo.test',   'JUNIOR26', true,  5],
            ['student',  'Sandrine', 'Mbarga',    'sandrine@demo.test', 'SANDRI26', true,  3],
            ['student',  'Kevin',    'Tchoua',    'kevin@demo.test',    'KEVIN026', true,  0],
            ['student',  'Compte',   'Désactivé', 'inactif@demo.test',  null,       false, null],
        ];

        $users = [];
        foreach ($people as $i => [$role, $first, $last, $email, $code, $active, $points]) {
            $user = User::updateOrCreate(['email' => $email], $this->cols(new User, [
                'role_id'          => Role::where('code', $role)->value('id'),
                'first_name'       => $first,
                'last_name'        => $last,
                'phone'            => '69000' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                'password'         => $hash,
                'password_hash'    => $hash,
                'delivery_address' => 'Campus de Yansoki',
                'is_active'        => $active,
            ]));

            if ($code) {
                ReferralCode::firstOrCreate(
                    ['user_id' => $user->id],
                    $this->cols(new ReferralCode, ['code' => $code, 'is_active' => true])
                );
            }

            if ($points !== null) {
                LoyaltyAccount::updateOrCreate(['user_id' => $user->id], ['balance_points' => $points]);
            }

            $users[$email] = $user;
        }

        return $users;
    }

    private function orders(array $users): array
    {
        $menu = MenuItem::where('is_available', true)->orderBy('id')->get()->values();
        if ($menu->isEmpty()) {
            return [];
        }

        // numéro, client, statut, mode, jours en arrière, paiement, méthode, [index du plat => quantité]
        $rows = [
            ['DEMO-001', 'amina@demo.test',    'completed', 'on_site',  0,  'paid',    'mobile_money', [0 => 2, 5 => 1]],
            ['DEMO-002', 'amina@demo.test',    'delivered', 'delivery', 2,  'paid',    'cash',         [1 => 1, 6 => 1]],
            ['DEMO-003', 'junior@demo.test',   'completed', 'on_site',  3,  'paid',    'mobile_money', [2 => 1, 5 => 1]],
            ['DEMO-004', 'junior@demo.test',   'ready',     'on_site',  0,  'pending', 'cash',         [0 => 1]],
            ['DEMO-005', 'sandrine@demo.test', 'preparing', 'delivery', 0,  'pending', 'mobile_money', [1 => 2]],
            ['DEMO-006', 'sandrine@demo.test', 'completed', 'on_site',  8,  'paid',    'cash',         [3 => 1, 7 => 1]],
            ['DEMO-007', 'kevin@demo.test',    'pending',   'on_site',  0,  'pending', 'cash',         [0 => 1, 5 => 1]],
            ['DEMO-008', 'kevin@demo.test',    'cancelled', 'on_site',  1,  'pending', 'cash',         [2 => 1]],
            ['DEMO-009', 'amina@demo.test',    'completed', 'on_site',  15, 'paid',    'mobile_money', [0 => 1, 4 => 1]],
            ['DEMO-010', 'junior@demo.test',   'completed', 'delivery', 25, 'paid',    'mobile_money', [1 => 1, 5 => 2]],
        ];

        $created = [];
        foreach ($rows as [$number, $email, $status, $mode, $daysAgo, $payStatus, $method, $lines]) {
            $user = $users[$email];
            $date = now()->subDays($daysAgo)->setTime(12, 30);
            $done = in_array($status, ['delivered', 'completed'], true);

            // Total calculé à partir des vrais prix du menu
            $subtotal = 0;
            foreach ($lines as $index => $qty) {
                $subtotal += $menu[$index % $menu->count()]->price_fcfa * $qty;
            }

            $order = Order::updateOrCreate(['order_number' => $number], $this->cols(new Order, [
                'user_id'               => $user->id,
                'service_mode'          => $mode,
                'delivery_address'      => $mode === 'delivery' ? 'Campus de Yansoki' : null,
                'subtotal_fcfa'         => $subtotal,
                'discount_fcfa'         => 0,
                'total_fcfa'            => $subtotal,
                'loyalty_points_used'   => 0,
                'loyalty_points_earned' => $done ? intdiv($subtotal, 1000) : 0,
                'status'                => $status,
                'created_at'            => $date,
                'updated_at'            => $date,
            ]));

            foreach ($lines as $index => $qty) {
                $dish = $menu[$index % $menu->count()];
                OrderItem::updateOrCreate(
                    ['order_id' => $order->id, 'menu_item_id' => $dish->id],
                    $this->cols(new OrderItem, [
                        'item_name'       => $dish->name,
                        'unit_price_fcfa' => $dish->price_fcfa,
                        'quantity'        => $qty,
                        'line_total_fcfa' => $dish->price_fcfa * $qty,
                    ])
                );
            }

            if ($status !== 'cancelled') {
                Payment::updateOrCreate(['order_id' => $order->id], $this->cols(new Payment, [
                    'provider'    => $method === 'cash' ? 'local' : 'cinetpay',
                    'method'      => $method,
                    'status'      => $payStatus,
                    'amount_fcfa' => $subtotal,
                    'paid_at'     => $payStatus === 'paid' ? $date : null,
                ]));
            }

            // Historique de points : ignoré sans erreur si la table exige d'autres colonnes
            if ($done && (int) $order->loyalty_points_earned > 0) {
                try {
                    LoyaltyTransaction::firstOrCreate(
                        ['user_id' => $user->id, 'order_id' => $order->id, 'type' => 'earn'],
                        $this->cols(new LoyaltyTransaction, [
                            'points'      => $order->loyalty_points_earned,
                            'description' => "Points gagnés sur la commande {$number}",
                        ])
                    );
                } catch (\Throwable $e) {
                    $this->command->warn('Historique de points ignoré : ' . $e->getMessage());
                }
            }

            $created[$number] = $order;
        }

        return $created;
    }

    private function referrals(array $users): void
    {
        $referrer = $users['amina@demo.test'];
        $code = ReferralCode::where('user_id', $referrer->id)->first();
        if (! $code) {
            return;
        }

        foreach ([['junior@demo.test', 'rewarded'], ['kevin@demo.test', 'pending']] as [$email, $status]) {
            Referral::firstOrCreate(
                ['referrer_user_id' => $referrer->id, 'referred_user_id' => $users[$email]->id],
                $this->cols(new Referral, [
                    'code_id'     => $code->id,
                    'status'      => $status,
                    'rewarded_at' => $status === 'rewarded' ? now()->subDays(5) : null,
                ])
            );
        }
    }

    private function claims(array $users, array $orders): void
    {
        if (! $orders) {
            return;
        }

        $handler = $users['employe@demo.test'];

        // client, commande, sujet, statut, réponse
        $rows = [
            ['amina@demo.test',  'DEMO-001', 'Plat reçu froid',     'open',        null],
            ['junior@demo.test', 'DEMO-003', 'Retard de livraison', 'in_progress', 'Nous sommes désolés pour ce retard, un geste commercial vous sera proposé.'],
            ['amina@demo.test',  'DEMO-009', 'Boisson manquante',   'resolved',    'La boisson vous a été remboursée en points de fidélité.'],
        ];

        foreach ($rows as [$email, $number, $subject, $status, $response]) {
            Claim::updateOrCreate(
                ['user_id' => $users[$email]->id, 'subject' => $subject],
                $this->cols(new Claim, [
                    'order_id'    => $orders[$number]->id,
                    'description' => "Réclamation de démonstration : {$subject}.",
                    'status'      => $status,
                    'response'    => $response,
                    'handled_by'  => $response ? $handler->id : null,
                ])
            );
        }
    }
}
