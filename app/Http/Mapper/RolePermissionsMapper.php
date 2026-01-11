<?php

namespace App\Http\Mapper;

use Illuminate\Support\Collection;

class RolePermissionsMapper
{
    public static function toTable(Collection $rolePermissions): Collection
    {
        return $rolePermissions
            ->groupBy(
                fn($item) =>
                $item->role_id . '|' . $item->permission->menu_id
            )
            ->map(function ($items) {

                $first = $items->first();

                return [
                    'role_id' => $first->role_id,
                    'menu_id' => $first->permission->menu_id,
                    'role'    => $first->role->nama_role,
                    'menu'    => $first->permission->menu->nama_menu,
                    'permissions' => $items
                        ->pluck('permission')
                        ->map(fn($p) => $p->akses)
                        ->toArray(),
                ];
            })
            ->values();
    }
}
