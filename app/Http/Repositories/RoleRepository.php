<?php

namespace App\Http\Repositories;

use App\Models\Role;

class RoleRepository
{
    public function get_all_data()
    {
        return Role::orderBy("created_at", "DESC")->get();
    }

    public function insert_data(array $data)
    {
        $supplier = Role::create([
            "nama_role" => $data["nama_role"],
            "is_active" => 1,
        ]);

        return $supplier;
    }

    public function get_data_by_id(string $id)
    {
        return Role::where("id", $id)->first();
    }

    public function update_by_id(string $id, array $data)
    {
        $supplier = Role::findOrFail($id);

        $supplier->update([
            "nama_role" => $data["nama_role"]
        ]);

        return $supplier;
    }

    public function delete_by_id(string $id): void
    {
        $supplier = Role::findOrFail($id);
        $supplier->delete();
    }
}
