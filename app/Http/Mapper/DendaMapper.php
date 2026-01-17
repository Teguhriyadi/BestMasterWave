<?php

namespace App\Http\Mapper;

use App\Models\DendaKaryawan;
use App\Models\JenisDenda;
use Illuminate\Support\Collection;

class DendaMapper
{
    public static function toTable(Collection $denda): Collection
    {
        return $denda->map(function(DendaKaryawan $item) {

            if ($item->status == "Draft") {
                $status = "<span class='badge bg-secondary text-white text-uppercase'>Draft</span>";
            } else if ($item->status == "Disetujui") {
                $status = "<span class='badge bg-success text-white text-uppercase'>Disetujui</span>";
            } else if ($item->status == "Dibatalkan") {
                $status = "<span class='badge bg-warning text-white text-uppercase'>Dibatalkan</span>";
            } else if ($item->status == "Dipotong") {
                $status = "<span class='badge bg-warning text-white text-uppercase'>Draft</span>";
            }

            return [
                'id'            => $item->id,
                'kode'          => $item->jenis_denda->kode,
                'jabatan'       => $item->karyawan->jabatan->nama_jabatan,
                'karyawan'      => $item->karyawan->nama,
                'tanggal'       => $item["tanggal_denda"]->locale('id')->translatedFormat('d F Y'),
                'jenis'         => $item->jenis_denda->nama_jenis,
                'keterangan'    => $item->keterangan,
                'periode_gaji'  => $item["periode_gaji"]->locale('id')->translatedFormat('d F Y'),
                'status'        => $status
            ];
        });
    }
}
