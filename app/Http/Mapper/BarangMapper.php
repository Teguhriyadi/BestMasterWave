<?php

namespace App\Http\Mapper;

use App\Models\Barang;
use App\Models\Supplier;
use BcMath\Number;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class BarangMapper
{
    public static function toTable(Collection $barang): Collection
    {
        return $barang->map(function(Barang $barang) {
            return [
                'id'                         => $barang->id,
                'sku_barang'                 => $barang->sku_barang,
                'harga_modal'                => number_format($barang->harga_modal, 0, ',', '.'),
                'harga_pembelian_terakhir'   => number_format($barang->harga_pembelian_terakhir, 0, ',', '.'),
                'tanggal_pembelian_terakhir' => $barang["tanggal_pembelian_terakhir"] == null ? null : Carbon::parse($barang["tanggal_pembelian_terakhir"])->translatedFormat('d F Y'),
                'seller_id'                  => empty($barang->seller) ? "-" : $barang->seller->nama,
                'status_sku'                 => $barang->status_sku === "A" ? "Aktif" : "Tidak Aktif",
                'divisi'                     => !empty(Auth::user()->one_divisi_roles) ? "A" : $barang["divisi"]["nama_divisi"]
            ];
        });
    }

    public static function toSkuTable(Collection $barang): Collection
    {
        return $barang->map(function(Barang $barang) {
            return [
                'id'                            => $barang->id,
                'sku_barang'                    => $barang->sku_barang,
                'harga_modal'                   => $barang->harga_modal,
            ];
        });
    }
}
