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
            ["id" => 1, "code" => "employee", "label" => "Employé"],
            ["id" => 2, "code" => "student", "label" => "Etudiant"],
            ["id" => 3, "code" => "manager", "label" => "Gérant"],
            ["id" => 4, "code" => "admin", "label" => "Administrateur"],
        ]);

        DB::table("app_settings")->insert([
            ["setting_key" => "loyalty_fcfa_per_point", "setting_value" => "1000"],
            ["setting_key" => "loyalty_points_per_1000_fcfa_discount", "setting_value" => "5"],
            ["setting_key" => "loyalty_discount_fcfa", "setting_value" => "1000"],
            ["setting_key" => "restaurant_open", "setting_value" => "08:00"],
            ["setting_key" => "restaurant_close", "setting_value" => "22:00 "],
        ]);
    }
}
