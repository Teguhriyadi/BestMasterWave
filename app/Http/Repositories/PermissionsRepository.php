<?php

namespace App\Http\Repositories;

use App\Models\Permission;

class PermissionsRepository
{
    public function get_all_data()
    {
        return Permission::orderBy("created_at", "DESC")->get();
    }

    public function insert_data(array $data)
    {
        $permissions = Permission::create([
            "nama" => $data["nama"],
            "akses" => $data["akses"],
            "menu_id" => $data["menu_id"]
        ]);

        return $permissions;
    }

    public function get_data_by_id(string $id)
    {
        return Permission::where("id", $id)->first();
    }

    public function update_by_id(string $id, array $data)
    {
        $permissions = Permission::findOrFail($id);

        $permissions->update([
            "nama" => $data["nama"],
            "akses" => $data["akses"],
            "menu_id" => $data["menu_id"]
        ]);

        return $permissions;
    }

    public function delete_by_id(string $id): void
    {
        $permissions = Permission::findOrFail($id);
        $permissions->delete();
    }
}
