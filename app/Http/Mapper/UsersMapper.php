<?php

namespace App\Http\Mapper;

use App\Models\User;
use App\Models\UserDivisiRole;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class UsersMapper
{
    public static function toTable(Collection $users): Collection
    {
        return $users->map(function(User $users) {
            return [
                'id'            => $users["id"],
                "user_id"       => $users["id"],
                "nama"          => $users["nama"],
                "username"      => $users["username"],
                "email"         => $users["email"],
                "status"        => $users["is_active"] == "1" ? "Aktif" : "Tidak Aktif",
                "divisi"        => empty($users["one_divisi_roles"]) ? "-" : $users["one_divisi_roles"]["divisi"]["nama_divisi"],
                "role"          => empty($users["one_divisi_roles"]) ? "-" : $users["one_divisi_roles"]["roles"]["nama_role"]
            ];
        });
    }

    public static function toEditTable(User|UserDivisiRole $data): array
    {
        $authHasDivisi = !empty(Auth::user()?->one_divisi_roles);

        if (!$authHasDivisi && $data instanceof User) {
            return [
                'id'                => $data->id,
                'nama'              => $data->nama,
                'username'          => $data->username,
                'nomor_handphone'   => $data->nomor_handphone,
                'alamat'            => $data->alamat,
                'email'             => $data->email,
                'status'            => $data->is_active == 1 ? 'Aktif' : 'Tidak Aktif',
                'divisi'            => '-',
                'role'              => optional($data->roles)->nama_role ?? '-',
            ];
        }

        if ($authHasDivisi && $data instanceof UserDivisiRole) {
            return [
                'id'                => $data->user->id,
                'nama'              => $data->user->nama,
                'username'          => $data->user->username,
                'email'             => $data->user->email,
                'nomor_handphone'   => $data->user->nomor_handphone,
                'alamat'            => $data->user->alamat,
                'status'            => $data->user->is_active == 1 ? 'Aktif' : 'Tidak Aktif',
                'divisi'            => optional($data->divisi)->nama_divisi ?? '-',
                'role'              => optional($data->roles)->nama_role ?? '-',
            ];
        }

        throw new \InvalidArgumentException('Data tidak sesuai dengan role Auth');
    }
}
