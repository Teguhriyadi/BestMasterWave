<?php

namespace App\Http\Mapper;

use App\Models\Barang;
use App\Models\Pembelian;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class PembelianMapper
{
    public static function toTable(Collection $pembelian): Collection
    {
        return $pembelian->map(function(Pembelian $pembelian) {
            return [
                'id'                  => $pembelian->id,
                'no_invoice'          => $pembelian->no_invoice,
                'tanggal_invoice'     => $pembelian->tanggal_invoice->locale('id')->translatedFormat('d F Y'),
                'tanggal_jatuh_tempo' => $pembelian->tanggal_jatuh_tempo->locale('id')->translatedFormat('d F Y'),
                'total_harga'         => number_format($pembelian->total_harga, 0, ',', '.'),
                'total_diskon'        => number_format($pembelian->total_diskon, 0, ',', '.'),
                'total_ppn'           => number_format($pembelian->total_ppn, 0, ',', '.'),
                'total_qty'           => number_format($pembelian->total_qty, 0, ',', '.'),
                'supplier'            => $pembelian->supplier->nama_supplier,
                'keterangan'          => $pembelian->keterangan,
                'divisi'              => !empty(Auth::user()->one_divisi_roles) ? "A" : $pembelian["divisi"]["nama_divisi"]
            ];
        });
    }
}
