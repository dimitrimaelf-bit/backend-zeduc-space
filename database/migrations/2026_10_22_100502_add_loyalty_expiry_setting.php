<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('app_settings')->updateOrInsert(
            ['setting_key' => 'loyalty_points_expiry_months'],
            ['setting_value' => '12', 'updated_at' => now()]
        );
    }

    public function down(): void
    {
        DB::table('app_settings')->where('setting_key', 'loyalty_points_expiry_months')->delete();
    }
};
