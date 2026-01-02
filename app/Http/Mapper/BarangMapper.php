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
                'harga_modal'                   => number_format($supplier->harga_modal, 0, ',', '.'),
                'harga_pembelian_terakhir'      => number_format($supplier->harga_pembelian_terakhir, 0, ',', '.'),
                'tanggal_pembelian_terakhir'    => $supplier["tanggal_pembelian_terakhir"] == null ? null : \Carbon\Carbon::parse($supplier['tanggal_pembelian_terakhir'])->format('Y-m-d H:i:s'),
                'seller_id'                     => empty($supplier->seller) ? "-" : $supplier->seller->nama,
                'status_sku'                    => $supplier->status_sku === "A" ? "Aktif" : "Tidak Aktif"
            ];
        });
    }

    public static function toSkuTable(Collection $supplier): Collection
    {
        return $supplier->map(function(Barang $supplier) {
            return [
                'id'                            => $supplier->id,
                'sku_barang'                    => $supplier->sku_barang,
                'harga_modal'                   => $supplier->harga_modal,
            ];
        });
    }
}
