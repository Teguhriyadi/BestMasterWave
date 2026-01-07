<?php

namespace App\Http\Repositories;

use App\Helpers\AuthDivisi;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class RoleRepository
{
    public function get_all_data()
    {
        if (empty(Auth::user()->one_divisi_roles)) {
            return Role::orderBy("created_at", "DESC")->get();
        } else {
            return Role::where("divisi_id", AuthDivisi::id())
                ->orderBy("created_at", "DESC")->get();
        }
    }

    public function insert_data(array $data)
    {
        $supplier = Role::create([
            "nama_role" => $data["nama_role"],
            "is_active" => "1",
            "created_by" => Auth::user()->id,
            "divisi_id" => AuthDivisi::id()
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
            "nama_role" => $data["nama_role"],
            "divisi_id" => AuthDivisi::id()
        ]);

        return $supplier;
    }

    public function delete_by_id(string $id): void
    {
        $supplier = Role::findOrFail($id);
        $supplier->delete();
    }
}
