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
            ->whereIn('type', ['menu', 'submenu'])
            ->orderBy("order")
            ->get();
    }

    public function get_menu()
    {
        return Menu::whereIn("type", ["submenu"])->get();
    }

    public function get_menus()
    {
        return Menu::whereIn("type", ["submenu", "menu"])->get();
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

        $oldType     = $menu->type;
        $oldParentId = $menu->parent_id;

        $newType     = $data['tipe_menu'];
        $newParentId = $data['parent_id'] ?? null;

        if ($oldType !== $newType || $oldParentId !== $newParentId) {

            $query = Menu::where('type', $newType);

            if ($newType !== 'header') {
                $query->where('parent_id', $newParentId);
            }

            $lastOrder = $query->max('order');
            $menu->order = $lastOrder ? $lastOrder + 1 : 1;
        }

        $menu->update([
            "type" => $data["tipe_menu"],
            "nama_menu" => $data["nama_menu"],
            "slug" => Str::slug($data["nama_menu"]),
            "url_menu" => $data['url'] ?? null,
            "icon" => $data['icon'] ?? null,
            "parent_id" => $data['parent_id'] ?? null
        ]);

        return $menu;
    }

    public function delete_by_id(string $id): void
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
    }

    public function sidebarBase()
    {
        return Menu::where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    public function swapOrder(Menu $current, string $direction): void
    {
        $query = Menu::where('type', $current->type);

        if ($current->type === 'menu') {
            $query->where('parent_id', $current->parent_id);
        }

        if ($current->type === 'submenu') {
            $query->where('parent_id', $current->parent_id);
        }

        $target = $direction === 'up'
            ? $query->where('order', '<', $current->order)->orderBy('order', 'DESC')->first()
            : $query->where('order', '>', $current->order)->orderBy('order', 'ASC')->first();

        if (! $target) return;

        [$current->order, $target->order] = [$target->order, $current->order];

        $current->save();
        $target->save();
    }
}
