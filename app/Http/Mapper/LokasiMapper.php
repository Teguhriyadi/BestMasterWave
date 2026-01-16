<?php

namespace App\Http\Mapper;

use App\Models\Bank;
use App\Models\Lokasi;
use Illuminate\Support\Collection;

class LokasiMapper
{
    public static function toTable(Collection $lokasi): Collection
    {
        return $lokasi->map(function(Lokasi $location) {
            return [
                'id'            => $location->id,
                "kode_lokasi"   => $location->kode_lokasi,
                'nama_lokasi'   => $location->nama_lokasi,
                'status'        => $location->is_active == "1" ? "Aktif" : "Tidak Aktif"
            ];
        });
    }
}
