<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::select("id")->first();

        Role::create([
            "nama_role" => "Super Admin",
            "is_active" => "1",
            "created_by" => $user["id"],
            "updated_by" => $user["id"]
        ]);

        Role::create([
            "nama_role" => "Finance",
            "is_active" => "1",
            "created_by" => $user["id"],
            "updated_by" => $user["id"]
        ]);

        Role::create([
            "nama_role" => "Purchasing",
            "is_active" => "1",
            "created_by" => $user["id"],
            "updated_by" => $user["id"]
        ]);
    }
}
