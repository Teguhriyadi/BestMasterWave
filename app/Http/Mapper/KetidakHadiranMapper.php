<?php

namespace App\Http\Mapper;

use App\Models\RekapAbsensi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class KetidakHadiranMapper
{
    public static function toTable(Collection $ketidakhadiran): Collection
    {
        return $ketidakhadiran->map(function(RekapAbsensi $absen) {
            if ($absen->status_approval == "Diajukan") {
                $approval = "<span class='badge bg-warning text-white text-uppercase'>Diajukan</span>";
            } else if ($absen->status_approval == "Ditolak") {
                $approval = "<span class='badge bg-danger text-white text-uppercase'>Ditolak</span>";
            } else if ($absen->status_approval == "Disetujui") {
                $approval = "<span class='badge bg-success text-white text-uppercase'>Disetujui</span>";
            }

            return [
                "id"       => $absen["id"],
                "divisi"   => empty(Auth::user()->one_divisi_roles) ? $absen["karyawan"]["divisi"]["nama_divisi"] : "-",
                "nama"     => $absen["karyawan"]["nama"],
                "status"   => $absen["status"],
                "status_approval" => $absen["status_approval"],
                "alasan"   => $absen["alasan"],
                "approval" => $approval,
                "tanggal"  => $absen["tanggal"]->locale('id')->translatedFormat('d F Y'),
                "upload"   => $absen["created_at"]->locale('id')->translatedFormat('d F Y H:i:s'),
            ];
        });
    }
}
