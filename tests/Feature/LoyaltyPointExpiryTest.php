<?php

namespace Tests\Feature;

use App\Models\LoyaltyAccount;
use App\Models\LoyaltyTransaction;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LoyaltyPointExpiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_expiry_command_removes_only_unspent_points_and_is_idempotent(): void
    {
        $role = Role::create(['code' => 'student', 'label' => 'Étudiant']);
        $user = User::create([
            'role_id' => $role->id,
            'first_name' => 'Amina',
            'last_name' => 'Test',
            'email' => 'expiry@example.test',
            'password' => 'Password123!',
        ]);
        $oldGrant = LoyaltyTransaction::create([
            'user_id' => $user->id,
            'type' => 'earn',
            'points' => 10,
            'description' => 'Lot ancien',
            'expires_at' => now()->subDay(),
        ]);
        DB::table('loyalty_transactions')->where('id', $oldGrant->id)->update([
            'created_at' => now()->subMonths(2),
        ]);
        $newGrant = LoyaltyTransaction::create([
            'user_id' => $user->id,
            'type' => 'earn',
            'points' => 3,
            'description' => 'Lot récent',
            'expires_at' => now()->addMonths(10),
        ]);
        DB::table('loyalty_transactions')->where('id', $newGrant->id)->update([
            'created_at' => now()->subMonth(),
        ]);
        $redemption = LoyaltyTransaction::create([
            'user_id' => $user->id,
            'type' => 'redeem',
            'points' => 4,
            'description' => 'Points utilisés avant échéance',
        ]);
        DB::table('loyalty_transactions')->where('id', $redemption->id)->update([
            'created_at' => now()->subDays(2),
        ]);
        LoyaltyAccount::create(['user_id' => $user->id, 'balance_points' => 9]);

        Artisan::call('loyalty:expire-points');
        Artisan::call('loyalty:expire-points');

        $this->assertSame(3, LoyaltyAccount::where('user_id', $user->id)->value('balance_points'));
        $this->assertSame(1, LoyaltyTransaction::where('type', 'expire')->where('source_transaction_id', $oldGrant->id)->count());
        $this->assertDatabaseHas('loyalty_transactions', [
            'source_transaction_id' => $oldGrant->id,
            'type' => 'expire',
            'points' => 6,
        ]);
    }
}
