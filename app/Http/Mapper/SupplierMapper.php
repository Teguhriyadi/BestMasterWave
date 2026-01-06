<?php

namespace App\Http\Mapper;

use App\Models\Supplier;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class SupplierMapper
{
    public static function toTable(Collection $supplier): Collection
    {
        return $supplier->map(function(Supplier $supplier) {
            return [
                'id'                => $supplier->id,
                'no_npwp'           => $supplier->no_npwp,
                'nama_supplier'     => $supplier->nama_supplier,
                'kontak_hubungi'    => $supplier->kontak_hubungi,
                'nomor_kontak'      => $supplier->nomor_kontak,
                'no_rekening'       => $supplier->no_rekening,
                'nama_rekening'     => $supplier->nama_rekening,
                'bank'              => $supplier->bank->alias,
                'alamat'            => $supplier->alamat,
                'tempo_pembayaran'  => $supplier->ketentuan_tempo_pembayaran,
                'status_pkp'        => $supplier->pkp,
                'divisi'            => !empty(Auth::user()->one_divisi_roles) ? "A" : $supplier["divisi"]["nama_divisi"]
            ];
        });
    }

    public static function toList(Collection $supplier): Collection
    {
        return $supplier->map(function(Supplier $supplier) {
            return [
                'id'                => $supplier->id,
                'nama_supplier'     => $supplier->nama_supplier,
                'ppn'               => $supplier->rate_ppn,
                'tempo_pembayaran'  => $supplier->ketentuan_tempo_pembayaran
            ];
        });
    }
}
