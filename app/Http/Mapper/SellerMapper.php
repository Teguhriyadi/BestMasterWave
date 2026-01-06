<?php

namespace App\Http\Mapper;

use App\Models\Seller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class SellerMapper
{
    public static function toTable(Collection $seller): Collection
    {
        return $seller->map(function(Seller $seller) {
            return [
                'id'          => $seller->id,
                'nama'        => $seller->nama,
                'slug'        => $seller->slug,
                'platform'    => $seller->platform->nama,
                'status'      => $seller->status == "1" ? "Aktif" : "Tidak Aktif",
                'divisi'      => !empty(Auth::user()->one_divisi_roles) ? "A" : $seller["divisi"]["nama_divisi"]
            ];
        });
    }

    public static function toListOption(Collection $seller): Collection
    {
        return $seller->map(function(Seller $seller) {
            return [
                'id'          => $seller->id,
                'nama'        => $seller->nama
            ];
        });
    }

    public static function toListSelectOption(Collection $supplier): Collection
    {
        return $supplier->map(function(Seller $supplier) {
            return [
                'id' => $supplier->id,
                'nama' => $supplier->nama
            ];
        });
    }
}
