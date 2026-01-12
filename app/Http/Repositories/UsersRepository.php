<?php

namespace App\Http\Repositories;

use App\Models\Divisi;
use App\Models\DivisiRole;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDivisiRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersRepository
{
    public function get_all_data()
    {
        return User::orderBy("created_at", "DESC")->get();
    }

    public function insert_data(array $data)
    {
        $cek = Role::select("id")->where("nama_role", "Super Admin")
            ->where("is_active", "1")
            ->first();

        $divisi = Divisi::select("slug")->where("id", $data["divisi_id"])
            ->first();

        $users = User::create([
            "nama" => $data["nama"],
            "username" => $data["username"],
            "email" => $data["email"],
            "password" => bcrypt("password"),
            "is_active" => "1",
            "nomor_handphone" => $data["nomor_handphone"] ?? null,
            "alamat" => $data["alamat"] ?? null
        ]);

        UserDivisiRole::create([
            "user_id" => $users["id"],
            "divisi_id" => $data["divisi_id"],
            "role_id" => $data["role_id"],
            "is_admin" => $data["role_id"] == $cek["id"] ? "1" : "0"
        ]);

        return $users;
    }

    public function get_data_by_id(string $id)
    {
        if (empty(Auth::user()->one_divisi_roles)) {
            return User::where("id", $id)->first();
        } else {
            return UserDivisiRole::where("id", $id)->first();
        }
    }

    public function update_by_id(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $user = User::findOrFail($id);

            $user->update([
                "nama"            => $data["nama"],
                "username"        => $data["username"],
                "email"           => $data["email"],
                "is_active"       => 1,
                "nomor_handphone" => $data["nomor_handphone"] ?? null,
                "alamat"          => $data["alamat"] ?? null
            ]);

            UserDivisiRole::where("user_id", $user->id)->update([
                'divisi_id'  => $data['divisi_id'],
                'role_id'    => $data["role_id"],
                'updated_at' => now(),
            ]);

            return $user;
        });
    }

    public function delete_by_id(string $id): void
    {
        $user = User::findOrFail($id);

        UserDivisiRole::where("user_id", $id)->delete();

        $user->delete();
    }

    public function getRolesByDivisi(string $divisionId)
    {
        return Divisi::with('roles:id,nama_role')
            ->findOrFail($divisionId)
            ->roles;
    }

    public function updateStatus(string $id): User
    {
        $user = User::findOrFail($id);

        if ($user->is_active == "1") {
            $user->update(['is_active' => "0"]);
        } else {
            $user->update(['is_active' => "1"]);
        }

        return $user;
    }
}
