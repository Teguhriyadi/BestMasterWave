<?php

namespace App\Http\Mapper;

use App\Models\Menu;
use Illuminate\Support\Collection;

class MenuMapper
{
    public static function toTable(Collection $menu): Collection
    {
        return $menu->map(function (Menu $menu) {
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

    public static function toListMenus(Collection $menus): Collection
    {
        return $menus
            ->groupBy(fn(Menu $menu) => $menu->header ?? 'LAINNYA')
            ->map(function (Collection $items, string $header) {

                $menusOnly = $items->where('type', 'menu');
                $subMenus  = $items->where('type', 'submenu')->groupBy('parent_id');

                $result = collect();

                foreach ($menusOnly as $menu) {
                    $result->push([
                        'id'   => $menu->id,
                        'text' => $menu->nama_menu,
                        'type' => 'menu',
                    ]);

                    if ($subMenus->has($menu->id)) {
                        foreach ($subMenus[$menu->id] as $sub) {
                            $result->push([
                                'id'   => $sub->id,
                                'text' => '- ' . $sub->nama_menu,
                                'type' => 'submenu',
                            ]);
                        }
                    }
                }

                return [
                    'label' => strtoupper($header),
                    'items' => $result
                ];
            })
            ->values();
    }
}
