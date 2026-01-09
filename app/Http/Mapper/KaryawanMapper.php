<?php

namespace App\Http\Mapper;

use App\Models\Bank;
use App\Models\Karyawan;
use Illuminate\Support\Collection;

class KaryawanMapper
{
    public static function toTable(Collection $karyawan): Collection
    {
        return $karyawan->map(function(Karyawan $item) {
            return [
                'id' => $item["id"],
                "sidik_jari" => $item["id_fp"],
                "no_ktp" => $item["no_ktp"],
                "no_kk" => $item["no_kk"],
                "no_bpjs_kesehatan" => $item["no_bpjs_kesehatan"],
                "nama" => $item["nama"],
                "tanggal_masuk" => $item["tanggal_masuk"],
                "no_hp" => $item["no_hp"],
                "no_hp_darurat" => $item["no_hp_darurat"],
                "jenis_kelamin" => $item["jenis_kelamin"] == "L" ? "Laki - Laki" : "Perempuan"
            ];
        });
    }
}
