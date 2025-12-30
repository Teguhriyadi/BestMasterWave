<?php

namespace App\Http\Mapper;

use App\Models\Barang;
use App\Models\Supplier;
use BcMath\Number;
use Illuminate\Support\Collection;

class BarangMapper
{
    public static function toTable(Collection $supplier): Collection
    {
        return $supplier->map(function(Barang $supplier) {
            return [
                'id'                            => $supplier->id,
                'sku_barang'                    => $supplier->sku_barang,
                'harga_modal'                   => number_format($supplier->harga_modal),
                'harga_pembelian_terakhir'      => number_format($supplier->kontak_hubungi),
                'tanggal_pembelian_terakhir'    => $supplier->tanggal_pembelian_terakhir,
                'seller_id'                     => $supplier->seller->nama,
                'status_sku'                    => $supplier->status_sku === "A" ? "Aktif" : "Tidak Aktif"
            ];
        });
    }
}
