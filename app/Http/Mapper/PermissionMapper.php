<?php

namespace App\Http\Mapper;

use App\Models\Permission;
use Illuminate\Support\Collection;

class PermissionMapper
{
    public static function toTable(Collection $permissions): Collection
    {
        return $permissions->map(function(Permission $permission) {
            $explode = explode('.', $permission->akses);

            $akses = $explode[0] ?? null;
            $tipe  = $explode[1] ?? null;

            return [
                'id'    => $permission->id,
                'nama'  => $permission->nama,
                'akses' => $akses,
                "tipe"  => ucfirst(str_replace('_', ' ', $tipe)),
                'menu'  => $permission->menu->nama_menu
            ];
        });
    }
}
