<?php

namespace App\Http\Mapper;

use App\Models\Menu;
use Illuminate\Support\Collection;

class MenuMapper
{
    public static function toTable(Collection $menu): Collection
    {
        return $menu->map(function(Menu $menu) {
            return [
                'id'            => $menu->id,
                'nama_menu'     => $menu->nama_menu,
                'slug'          => $menu->slug,
                'type'          => $menu->type,
                'url_menu'      => $menu->url_menu,
                'ikon'          => $menu->icon,
                'order'         => $menu->order,
                'parent_menu'   => $menu->parent?->nama_menu ?? '-',
                'status'        => $menu->is_active == 1 ? "Aktif" : "Tidak Aktif"
            ];
        });
    }
}
