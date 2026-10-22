<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loyalty_transactions', function (Blueprint $table) {
            $table->foreignId('source_transaction_id')
                ->nullable()
                ->unique()
                ->constrained('loyalty_transactions')
                ->nullOnDelete();
        });

        DB::table('app_settings')->updateOrInsert(
            ['setting_key' => 'privacy_policy_url'],
            ['setting_value' => '', 'updated_at' => now()]
        );
        DB::table('app_settings')->updateOrInsert(
            ['setting_key' => 'terms_of_service_url'],
            ['setting_value' => '', 'updated_at' => now()]
        );
        DB::table('app_settings')->updateOrInsert(
            ['setting_key' => 'cookie_policy_version'],
            ['setting_value' => '1.0', 'updated_at' => now()]
        );
    }

    public function down(): void
    {
        Schema::table('loyalty_transactions', function (Blueprint $table) {
            $table->dropForeign(['source_transaction_id']);
            $table->dropUnique(['source_transaction_id']);
            $table->dropColumn('source_transaction_id');
        });

        DB::table('app_settings')
            ->whereIn('setting_key', ['privacy_policy_url', 'terms_of_service_url', 'cookie_policy_version'])
            ->delete();
    }
};
