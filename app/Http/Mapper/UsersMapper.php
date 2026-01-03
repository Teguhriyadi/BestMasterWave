<?php

namespace App\Http\Mapper;

use App\Models\User;
use Illuminate\Support\Collection;

class UsersMapper
{
    public static function toTable(Collection $users): Collection
    {
        return $users->map(function(User $users) {
            return [
                'id'            => $users["id"],
                "nama"          => $users["nama"],
                "username"      => $users["username"],
                "email"         => $users["email"],
                "status"        => $users["is_active"] == "1" ? "Aktif" : "Tidak Aktif"
            ];
        });
    }
}
