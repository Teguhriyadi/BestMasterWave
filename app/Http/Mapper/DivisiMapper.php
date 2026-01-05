<?php

namespace App\Http\Mapper;

use App\Models\Divisi;
use App\Models\Supplier;
use Illuminate\Support\Collection;

class DivisiMapper
{
    public static function toTable(Collection $divisi): Collection
    {
        return $divisi->map(function(Divisi $divisi) {
            return [
                "id"          => $divisi["id"],
                "nama_divisi" => $divisi["nama_divisi"],
                "slug"        => $divisi["slug"],
                "status"      => $divisi["is_active"] == "1" ? "Aktif" : "Tidak Aktif",
            ];
        });
    }
}
