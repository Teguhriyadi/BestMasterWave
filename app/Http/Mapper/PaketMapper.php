<?php

namespace App\Http\Mapper;

use App\Models\Bank;
use Illuminate\Support\Collection;

class PaketMapper
{
    public static function toViewModel($paket)
    {
        return (object) [
            'id'            => $paket->id,
            'sku'           => $paket->sku_paket,
            'nama'          => $paket->nama_paket,
            'harga_display' => number_format($paket->harga_jual, 0, ',', '.'),
            'harga_raw'     => $paket->harga_jual,
            // 'stok_virtual'  => $paket->stok_virtual, // Memanggil Accessor dari Model
            'stok_virtual' => 1,
            'items'         => $paket->items->map(function($item) {
                return (object) [
                    // 'nama_barang' => $item->barangs->nama_barang,
                    'sku_barang'    => $item->barangs->sku_barang,
                    'qty'           => $item->qty,
                    'harga_satuan'  => $item->harga_satuan
                    // 'subtotal_modal' => $item->harga_modal_snapshot * $item->qty
                ];
            })
        ];
    }
}
