<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Platform::create([
            "nama" => "Shopee",
            "slug" => "shopee",
            "status" => "1"
        ]);

        Platform::create([
            "nama" => "Tiktok",
            "slug" => "tiktok",
            "status" => "1"
        ]);

        Platform::create([
            "nama" => "Lazada",
            "slug" => "lazada",
            "status" => "1"
        ]);
    }
}
