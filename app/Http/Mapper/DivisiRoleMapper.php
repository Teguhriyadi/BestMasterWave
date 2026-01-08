<?php

namespace App\Http\Mapper;

use App\Models\Divisi;
use App\Models\DivisiRole;
use App\Models\User;
use App\Models\UserDivisiRole;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class DivisiRoleMapper
{
    public static function toTable(Collection $divisi_role): Collection
    {
        return $divisi_role->map(function (Divisi $division) {
            return [
                'id'          => $division->id,
                'nama_divisi' => $division->nama_divisi,
                'roles'       => $division->roles->map(fn ($role) => $role->nama_role),
            ];
        });
    }

    public static function toListData(Collection $user_divisi_role): Collection
    {
        return $user_divisi_role->map(function (UserDivisiRole $users_role) {
            return [
                'id'        => $users_role["id"],
                'nama'      => empty(Auth::user()->one_divisi_roles) ? null : $users_role["user"]["nama"],
                'username'  => empty(Auth::user()->one_divisi_roles) ? null : $users_role["user"]["username"],
                'email'  => empty(Auth::user()->one_divisi_roles) ? null : $users_role["user"]["email"],
                'divisi'  => empty(Auth::user()->one_divisi_roles) ? null : $users_role["divisi"]["nama_divisi"],
                'role'  => empty(Auth::user()->one_divisi_roles) ? null : $users_role["roles"]["nama_role"],
                'status' => empty(Auth::user()->one_divisi_roles)
                    ? null
                    : (
                        $users_role['user']['is_active'] == 1
                            ? 'Aktif'
                            : 'Tidak Aktif'
                    ),

            ];
        });
    }
}
