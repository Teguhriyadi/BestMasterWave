<?php

namespace App\Http\Mapper;

use App\Models\Jabatan;
use Illuminate\Support\Collection;

class JabatanMapper
{
    public static function toTable(Collection $jabatan): Collection
    {
        return $jabatan->map(function(Jabatan $item) {
            return [
                "id"          => $item["id"],
                "jabatan"     => $item["nama_jabatan"],
                "slug"        => $item["slug"],
                "status"      => $item["is_active"] == "1" ? "Aktif" : "Tidak Aktif",
            ];
        });
    }
}
