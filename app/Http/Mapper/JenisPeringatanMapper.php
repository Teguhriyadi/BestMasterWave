<?php

namespace App\Http\Mapper;

use App\Models\JenisDenda;
use App\Models\JenisPeringatan;
use Illuminate\Support\Collection;

class JenisPeringatanMapper
{
    public static function toTable(Collection $jenis_peringatan): Collection
    {
        return $jenis_peringatan->map(function(JenisPeringatan $jenis) {
            $status = $jenis->is_active == "1" ? "<span class='badge bg-success text-white text-uppercase'>Aktif</span>" : "<span class='badge bg-danger text-white text-uppercase'>Tidak Aktif</span>";

            return [
                'id'                => $jenis->id,
                'kode'              => $jenis->kode,
                'nama_peringatan'   => $jenis->nama_peringatan,
                'level'             => $jenis->level,
                'masa_berlaku'      => $jenis->masa_berlaku_hari,
                'keterangan'        => $jenis->keterangan,
                'status'            => $status
            ];
        });
    }

    public static function toListPeringatan(Collection $jenis_peringatan): Collection
    {
        return $jenis_peringatan->map(function(JenisPeringatan $jenis) {
            return [
                'id'                => $jenis->id,
                'kode'              => $jenis->kode,
                'nama_peringatan'   => $jenis->nama_peringatan,
                'level'             => $jenis->level,
                "masa_berlaku_hari" => $jenis->masa_berlaku_hari
            ];
        });
    }
}
