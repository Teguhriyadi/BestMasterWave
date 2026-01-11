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

    public function insert_data(array $data)
    {
        $menu = Menu::create([
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
}
