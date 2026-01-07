<?php

namespace App\Http\Mapper;

use App\Models\Role;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class RoleMapper
{
    public static function toTable(Collection $role): Collection
    {
        return $role->map(function(Role $role) {
            return [
                'id'          => $role["id"],
                "nama_role"   => $role["nama_role"],
                "status"      => $role["is_active"] == "1" ? "Aktif" : "Tidak Aktif",
                'divisi'      => !empty(Auth::user()->one_divisi_roles) ? "A" : $role["divisi"]["nama_divisi"]
            ];
        });
    }
}
