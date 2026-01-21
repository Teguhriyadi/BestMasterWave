<?php

namespace App\Http\Mapper;

use App\Models\Divisi;
use App\Models\DivisiRole;
use App\Models\Role;
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

    public static function toListRole(Collection $divisi_role): Collection
    {
        return $divisi_role->map(function (DivisiRole $division) {
            return [
                'id'          => $division->role_id,
                'nama_role'   => $division->role->nama_role
            ];
        });
    }

    public static function toPermissionsRole(Collection $role_permissions): Collection
    {
        return $role_permissions->map(function (Role $role) {
            return [
                'id'          => $role->id,
                'nama_role'   => $role->nama_role
            ];
        });
    }

    public static function toPermissionsRoleNoSuperAdmin(Collection $role_permissions): Collection
    {
        return $role_permissions->map(function (DivisiRole $role) {
            return [
                'id'          => $role->role->id,
                'nama_role'   => $role->role->nama_role
            ];
        });
    }

    public static function toListData(Collection $user_divisi_role): Collection
    {
        return $user_divisi_role->map(function (UserDivisiRole $users_role) {
            return [
                'id'        => $users_role["id"],
                "user_id"      => empty(Auth::user()->one_divisi_roles) ? null : $users_role["user"]["id"],
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
