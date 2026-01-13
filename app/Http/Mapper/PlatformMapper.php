<?php

namespace App\Http\Mapper;

use App\Models\Platform;
use Illuminate\Support\Collection;

class PlatformMapper
{
    public static function toTable(Collection $platform): Collection
    {
        return $platform->map(function(Platform $platform) {
            return [
                "id"          => $platform["id"],
                "nama"        => $platform["nama"],
                "slug"        => $platform["slug"],
                "status"      => $platform["status"] == "1" ? "Aktif" : "Tidak Aktif"
            ];
        });
    }
}
