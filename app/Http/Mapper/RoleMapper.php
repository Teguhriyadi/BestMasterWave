<?php

namespace App\Http\Mapper;

use App\Models\Role;
use Illuminate\Support\Collection;

class RoleMapper
{
    public static function toTable(Collection $role): Collection
    {
        return $role->map(function(Role $role) {
            return [
                'id'          => $role["id"],
                "nama_role"   => $role["nama_role"],
                "status"      => $role["is_active"] == "1" ? "Aktif" : "Tidak Aktif"
            ];
        });
    }
}
