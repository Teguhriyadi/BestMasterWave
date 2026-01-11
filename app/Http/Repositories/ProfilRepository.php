<?php

namespace App\Http\Repositories;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Support\Str;

class ProfilRepository
{
    public function get_data_by_auth(string $id)
    {
        return User::where("id", $id)->first();
    }

    public function update_by_auth(string $id, array $data)
    {
        $user = User::findOrFail($id);

        $user->update([
            "email" => $data["email"],
            "nama" => $data["nama"]
        ]);

        return $user;
    }

    public function change_password(string $id, array $data)
    {
        $user = User::findOrFail($id);

        $user->update([
            "password" => bcrypt($data["password"])
        ]);

        return $user;
    }
}
