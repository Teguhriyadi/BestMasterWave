<?php

namespace App\Http\Mapper;

use App\Models\Divisi;
use App\Models\DivisiRole;
use Illuminate\Support\Collection;

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
}
