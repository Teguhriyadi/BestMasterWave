<?php

namespace App\Http\Mapper;

use App\Models\JenisDenda;
use Illuminate\Support\Collection;

class JenisDendaMapper
{
    public static function toTable(Collection $jenis_denda): Collection
    {
        return $jenis_denda->map(function(JenisDenda $jenis) {
            $statusJenisDenda = $jenis['is_active'] == "1"
                ? '<span class="badge bg-success text-white text-uppercase">Aktif</span>'
                : '<span class="badge bg-danger text-white text-uppercase">Tidak Aktif</span>';

            return [
                'id'            => $jenis->id,
                'kode'          => $jenis->kode,
                'nama_jenis'    => $jenis->nama_jenis,
                'nominal'       => number_format($jenis->nominal, 0, ',', '.'),
                'keterangan'    => $jenis->keterangan,
                'status'        => $statusJenisDenda
            ];
        });
    }
}
