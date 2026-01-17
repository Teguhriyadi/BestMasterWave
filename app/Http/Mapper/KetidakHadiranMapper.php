<?php

namespace App\Http\Mapper;

use App\Models\RekapAbsensi;
use Illuminate\Support\Collection;

class KetidakHadiranMapper
{
    public static function toTable(Collection $ketidakhadiran): Collection
    {
        return $ketidakhadiran->map(function(RekapAbsensi $absen) {
            return [
                "id"      => $absen["id"],
                "nama"    => $absen["karyawan"]["nama"],
                "status"  => $absen["status"],
                "alasan"  => $absen["alasan"],
                "tanggal" => $absen["tanggal"],
                "upload"  => $absen["created_at"]
            ];
        });
    }
}
