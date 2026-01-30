<?php

namespace App\Http\Repositories;

use App\Helpers\AuthDivisi;
use App\Models\Platform;
use App\Models\Seller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SellerRepository
{
    public function get_all_data()
    {
        if (empty(Auth::user()->one_divisi_roles)) {
            return Seller::orderBy("created_at", "DESC")
                ->with("platform")->get();
        } else {
            return Seller::where("divisi_id", AuthDivisi::id())
            ->orderBy("created_at", "DESC")
            ->with("platform")->get();
        }
    }

    public function list_data_seller()
    {
        $platform = Platform::where("nama", "Shopee")->where("status", "1")
            ->first();

        return Seller::where("platform_id", $platform["id"])
            ->where("status", "1")
            ->get();
    }

    public function list_data_seller_all()
    {
        $platform = Platform::where("nama", "Shopee")->where("status", "1")
            ->first();

        return Seller::where("status", "1")
            ->get();
    }

    public function list_data_seller_tiktok()
    {
        $platform = Platform::where("nama", "tiktok")->where("status", "1")
            ->first();

        return Seller::where("platform_id", $platform["id"])
            ->where("status", "1")
            ->get();
    }

    public function list_data_seller_by_id()
    {
        $platform = Platform::where("nama", "Shopee")->where("status", "1")
            ->first();

        return Seller::where("platform_id", $platform["id"])
            ->where("divisi_id", AuthDivisi::id())
            ->where("status", "1")
            ->get();
    }

    public function list_seller_shopee()
    {
        $platform = Platform::where("nama", "Shopee")->where("status", "1")
            ->first();

        return Seller::where("platform_id", $platform["id"])
            ->where("divisi_id", AuthDivisi::id())
            ->where("status", "1")
            ->get();
    }

    public function list_seller_tiktok()
    {
        $platform = Platform::where("nama", "Tiktok")->where("status", "1")
            ->first();

        return Seller::where("platform_id", $platform["id"])
            ->where("divisi_id", AuthDivisi::id())
            ->where("status", "1")
            ->get();
    }

    public function insert_data(array $data)
    {
        $supplier = Seller::create([
            "platform_id" => $data["platform_id"],
            "nama" => $data["nama"],
            "slug" => Str::slug($data["nama"]),
            "divisi_id" => AuthDivisi::id(),
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
            "slug" => Str::slug($data["nama"]),
            "divisi_id" => AuthDivisi::id()
        ]);

        return $seller;
    }

    public function delete_by_id(string $id): void
    {
        $supplier = Seller::findOrFail($id);
        $supplier->delete();
    }
}
