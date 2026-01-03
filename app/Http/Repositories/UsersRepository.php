<?php

namespace App\Http\Repositories;

use App\Models\Divisi;
use App\Models\DivisiRole;
use App\Models\User;
use App\Models\UserDivisiRole;
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
        $users = User::create([
            "nama" => $data["nama"],
            "username" => $data["username"],
            "email" => $data["email"],
            "password" => bcrypt($data['password']),
            "is_active" => "1",
            "nomor_handphone" => $data["nomor_handphone"] ?? null,
            "alamat" => $data["alamat"] ?? null
        ]);

        foreach ($data["role_id"] as $item) {
            UserDivisiRole::create([
                "user_id" => $users["id"],
                "divisi_id" => $data["divisi_id"],
                "role_id" => $item
            ]);
        }

        return $users;
    }

    public function get_data_by_id(string $id)
    {
        return User::where("id", $id)->first();
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

            UserDivisiRole::where('user_id', $user->id)
                ->where('divisi_id', $data['divisi_id'])
                ->delete();

            if (!empty($data['role_id'])) {
                $payload = [];

                foreach ($data['role_id'] as $roleId) {
                    $payload[] = [
                        'id'         => (string) Str::uuid(),
                        'user_id'    => $user->id,
                        'divisi_id'  => $data['divisi_id'],
                        'role_id'    => $roleId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                UserDivisiRole::insert($payload);
            }

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
}
