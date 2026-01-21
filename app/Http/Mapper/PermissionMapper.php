<?php

namespace App\Http\Mapper;

use App\Models\Permission;
use Illuminate\Support\Collection;

class PermissionMapper
{
    public static function toTable(Collection $permissions): Collection
    {
        return $permissions
            ->groupBy(fn($p) => $p->nama . '|' . $p->menu_id . '|' . explode('.', $p->akses)[0])
            ->map(function ($group) {

                $first = $group->first();

                $aksesBase = explode('.', $first->akses)[0];

                $tipeList = $group->map(function ($p) {
                    return ucfirst(str_replace('_', ' ', explode('.', $p->akses)[1] ?? ''));
                })->implode(', ');

                return [
                    'id'    => $first->id,
                    'nama'  => $first->nama,
                    'akses' => $aksesBase,
                    'tipe'  => $tipeList,
                    'menu'  => $first->menu->nama_menu
                ];
            })
            ->values();
    }
}
