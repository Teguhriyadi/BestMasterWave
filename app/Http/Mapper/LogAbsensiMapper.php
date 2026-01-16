<?php

namespace App\Http\Mapper;

use App\Models\Barang;
use App\Models\LogAbsensi;
use App\Models\Supplier;
use BcMath\Number;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class LogAbsensiMapper
{
    public static function toTable(Collection $log_absensi): Collection
    {
        return $log_absensi->map(function(LogAbsensi $log_absensi) {
            return [
                'id'             => $log_absensi->id,
                'nama_karyawan'  => $log_absensi["karyawan"]["nama"],
                'tanggal_waktu'  => $log_absensi->tanggal_waktu->locale('id')->translatedFormat('d F Y'),
                'divisi'         => !empty(Auth::user()->one_divisi_roles) ? "-" : $log_absensi["divisi"]["nama_divisi"]
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
