<?php

namespace Database\Seeders;

use App\Models\Divisi;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::select("id")->first();

        Divisi::create([
            "nama_divisi" => "Ciputat",
            "slug" => "ciputat",
            "is_active" => "1",
            "created_by" => $user["id"],
            "updated_by" => $user["id"]
        ]);

        Divisi::create([
            "nama_divisi" => "Bintaro",
            "slug" => "bintaro",
            "is_active" => "1",
            "created_by" => $user["id"],
            "updated_by" => $user["id"]
        ]);
    }
}
