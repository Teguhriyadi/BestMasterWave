<?php

namespace App\Http\Mapper;

use App\Models\Bank;
use App\Models\SetupJamKerja;
use Illuminate\Support\Collection;

class SetupJamKerjaMapper
{
    public static function toTable(Collection $jam_kerja): Collection
    {
        return $jam_kerja->map(function(SetupJamKerja $item) {
            return [
                'id'             => $item->id,
                'jam_masuk'      => $item->jam_masuk,
                'jam_pulang'     => $item->jam_pulang,
                'divisi'         => $item->divisi->nama_divisi
            ];
        });
    }
}
