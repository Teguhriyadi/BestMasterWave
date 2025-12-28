<?php

namespace App\Http\Mapper;

class RoleMapper
{
    public static function toTable($role)
    {
        return [
            "id" => $role->id,
            "nama_role" => $role->nama_role,
            "is_active" => $role->is_active
        ];
    }
}
