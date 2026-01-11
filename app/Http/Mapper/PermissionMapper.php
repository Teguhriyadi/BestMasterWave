<?php

namespace App\Http\Mapper;

use App\Models\Permission;
use Illuminate\Support\Collection;

class PermissionMapper
{
    public static function toTable(Collection $permissions): Collection
    {
        return $permissions->map(function(Permission $permission) {
            return [
                'id'    => $permission->id,
                'nama'  => $permission->nama,
                'akses' => $permission->akses,
                'menu'  => $permission->menu->nama_menu
            ];
        });
    }
}
