<?php

namespace App\Http\Repositories;

use App\Models\Seller;
use Illuminate\Support\Str;

class SellerRepository
{
    public function get_all_data()
    {
        return Seller::orderBy("created_at", "DESC")
            ->with("platform")->get();
    }

    public function insert_data(array $data)
    {
        $supplier = Seller::create([
            "platform_id" => $data["platform_id"],
            "nama" => $data["nama"],
            "slug" => Str::slug($data["nama"]),
            "status" => 1
        ]);

        return $supplier;
    }

    public function get_data_by_id(string $id)
    {
        return Seller::where("id", $id)->first();
    }

    public function update_by_id(string $id, array $data)
    {
        $seller = Seller::findOrFail($id);

        $seller->update([
            "platform_id" => $data["platform_id"],
            "nama" => $data["nama"],
            "slug" => Str::slug($data["nama"])
        ]);

        return $seller;
    }

    public function delete_by_id(string $id): void
    {
        $supplier = Seller::findOrFail($id);
        $supplier->delete();
    }
}
