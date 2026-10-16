<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class RolesAndSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("roles")->insert([
            ["code" => "student", "label" => "Etudiant"],
            ["code" => "employee", "label" => "Employé"],
            ["code" => "manager", "label" => "Gérant"],
            ["code" => "admin", "label" => "Administrateur"],
        ]);

        DB::table("app_settings")->insert([
            ["setting_key" => "loyalty_fcfa_per_point", "setting_value" => "1000"],
            ["setting_key" => "loyalty_points_per_1000_fcfa_discount", "setting_value" => "15"],
            ["setting_key" => "loyalty_discount_fcfa", "setting_value" => "1000"],
            ["setting_key" => "restaurant_open", "setting_value" => "08:00"],
            ["setting_key" => "restaurant_close", "setting_value" => "22:00 "],
        ]);
    }
}
