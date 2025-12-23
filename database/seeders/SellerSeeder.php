<?php

namespace Database\Seeders;

use App\Models\Platform;
use App\Models\Seller;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shopee = Platform::where("slug", "shopee")->first();
        $tiktok = Platform::where("slug", "tiktok")->first();
        $lazada = Platform::where("slug", "lazada")->first();

        $shopee_data = [
            'BARANG IMPORT TERMURAH JAKARTA',
            'BMW Kitchenware Official',
            'Eagle Crownware',
            'Flowery Bee',
            'Horeca Mall Jakarta',
            'jakartastainlesssteel',
            'NomiYumi',
            'Okela Official Shop',
            'Serba Murah_Jakarta',
            'top100.online',
            'Gomasta Official Shop',
            'GROSIR IMPORT LUAR NEGERI',
            'Hoco Indonesia Official Shop',
            'La Nelle Official Shop',
            'Murah Lebay Grosir Mall',
            'PapiTing',
            'Star Land Mall',
        ];

        foreach ($shopee_data as $name) {
            Seller::create([
                'platform_id' => $shopee->id,
                'nama' => $name,
                'slug' => Str::slug($name),
                'status' => '1'
            ]);
        }

        $tiktok_data = [
            'BestMasterWare.id',
            'BMW Kitchen Ware',
            'Golden Eagle',
            'Happy Bull',
            'Serba Serbi Grosir',
            'pilihan emak',
            'murah lebay',
        ];

        foreach ($tiktok_data as $name) {
            Seller::create([
                'platform_id' => $tiktok->id,
                'nama' => $name,
                'slug' => Str::slug($name),
                'status' => '1'
            ]);
        }

        $lazada_data = [
            "BMW Kitchenware",
            "HORECA JAKARTA",
            "Jakarta Stainless Steel",
            "Serba Murah Jakarta",
            "Top100.Online lzd",
            "Gomasta"
        ];

        foreach ($lazada_data as $name) {
            Seller::create([
                'platform_id' => $lazada->id,
                'nama' => $name,
                'slug' => Str::slug($name),
                'status' => '1'
            ]);
        }
    }
}
