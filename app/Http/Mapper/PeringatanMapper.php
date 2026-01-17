<?php

namespace App\Http\Mapper;

use App\Models\PeringatanKaryawan;
use Illuminate\Support\Collection;

class PeringatanMapper
{
    public static function toTable(Collection $peringatan): Collection
    {
        return $peringatan->map(function (PeringatanKaryawan $item) {

            if ($item['status'] == "Draft") {
                $status = "<span class='badge bg-secondary text-white text-uppercase'>Draft</span>";
            } else if ($item['status'] == "Aktif") {
                $status = "<span class='badge bg-success text-white text-uppercase'>Aktif</span>";
            } else if ($item['status'] == "Expired") {
                $status = "<span class='badge bg-danger text-white text-uppercase'>Expired</span>";
            } else if ($item['status'] == "Dicabut") {
                $status = "<span class='badge bg-primary text-white text-uppercase'>Dicabut</span>";
            }

            return [
                "id"                    => $item["id"],
                "karyawan"              => $item["karyawan"]["nama"],
                "jenis_peringatan"      => $item["jenis_peringatan"]["nama_peringatan"],
                "tanggal_pelanggaran"   => $item->tanggal_pelanggaran->locale('id')->translatedFormat('d F Y'),
                "tanggal_terbit_sp"     => $item->tanggal_terbit_sp->locale('id')->translatedFormat('d F Y'),
                "berlaku_sampai"        => $item->berlaku_sampai->locale('id')->translatedFormat('d F Y'),
                "keterangan"            => $item['keterangan'],
                "status"                => $status
            ];
        });
    }
}
