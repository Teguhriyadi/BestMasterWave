<?php

namespace App\Http\Repositories;

use App\Models\Bank;
use App\Models\Menu;
use Illuminate\Support\Str;

class MenuRepository
{
    public function get_all_data()
    {
        return Menu::orderBy("created_at", "DESC")->get();
    }

    public function get_parent()
    {
        return Menu::whereIn("type", ["menu"])->get();
    }

    public function get_parent_header()
    {
        return Menu::whereIn("type", ["header"])
            ->orderBy("order", "ASC")->get();
    }

    public function get_grouping_menu()
    {
        return Menu::with(["permissions"])
            ->where("type", "submenu")
            ->orderBy("order")
            ->get();
    }

    public function get_menu()
    {
        return Menu::whereIn("type", ["submenu"])->get();
    }

    public function insert_data(array $data)
    {
        $query = Menu::query();

        if ($data['tipe_menu'] == 'header') {
            $query->where('type', 'header');
        } else {
            $query->where('parent_id', $data['parent_id']);
        }

        $lastOrder = $query->max('order');
        $nextOrder = $lastOrder ? $lastOrder + 1 : 1;

        $menu = Menu::create([
            "type" => $data["tipe_menu"],
            "nama_menu" => $data["nama_menu"],
            "slug" => Str::slug($data["nama_menu"]),
            "url_menu" => $data['url'] ?? null,
            "icon" => $data['icon'] ?? null,
            "parent_id" => $data['parent_id'] ?? null,
            "order" => $nextOrder
        ]);

        return $menu;
    }

    public function get_data_by_id(string $id)
    {
        return Menu::where("id", $id)->first();
    }

    public function update_by_id(string $id, array $data)
    {
        $menu = Menu::findOrFail($id);

        $menu->update([
            "type" => $data["tipe_menu"],
            "nama_menu" => $data["nama_menu"],
            "slug" => Str::slug($data["nama_menu"]),
            "url_menu" => $data['url'] ?? null,
            "icon" => $data['icon'] ?? null,
            "parent_id" => $data['parent_id'] ?? null,
            "order" => $data['order'] ?? 0
        ]);

        return $menu;
    }

    public function delete_by_id(string $id): void
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
    }

    public function sidebarMenu(string $roleId, string $divisiId)
    {
        $globalHeaders = ['app'];
        $globalMenus   = ['dashboard'];

        $menus = Menu::with([
            'children.children.permissions.rolePermissions' => function ($q) use ($roleId, $divisiId) {
                $q->where('role_id', $roleId)
                    ->where('divisi_id', $divisiId);
            }
        ])
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $allowedSubmenus = $menus->filter(function ($menu) use ($globalMenus, $menus) {

            if ($menu->type !== 'submenu') {
                return false;
            }

            if ($menu->permissions->flatMap->rolePermissions->isEmpty()) {
                return false;
            }

            $parent = $menus->firstWhere('id', $menu->parent_id);

            return $parent && !in_array($parent->slug, $globalMenus);
        });

        $allowedMenus = $menus->filter(function ($menu) use ($allowedSubmenus, $globalMenus) {

            if ($menu->type !== 'menu') {
                return false;
            }

            if (in_array($menu->slug, $globalMenus)) {
                return true;
            }

            return $allowedSubmenus->where('parent_id', $menu->id)->isNotEmpty();
        });

        $allowedHeaders = $menus->filter(function ($menu) use ($allowedMenus, $globalHeaders) {

            if ($menu->type !== 'header') {
                return false;
            }

            if (in_array($menu->slug, $globalHeaders)) {
                return true;
            }

            return $allowedMenus->where('parent_id', $menu->id)->isNotEmpty();
        });

        return $allowedHeaders
            ->merge($allowedMenus)
            ->merge($allowedSubmenus)
            ->sortBy('order')
            ->values();
    }
}
