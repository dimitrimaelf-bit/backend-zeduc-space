<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['restaurant_open', 'restaurant_close'] as $key) {
            $value = DB::table('app_settings')->where('setting_key', $key)->value('setting_value');
            if ($value !== null) {
                DB::table('app_settings')->where('setting_key', $key)->update([
                    'setting_value' => trim($value),
                ]);
            }
        }
    }

    public function down(): void
    {
        // Whitespace normalization is intentionally irreversible.
    }
};
