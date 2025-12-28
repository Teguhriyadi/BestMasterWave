<?php

namespace App\Http\Repositories;

use App\Models\Role;

class RoleRepository
{
    public function all_data()
    {
        return Role::get();
    }

    public function store(array $data): Role
    {
        return Role::create($data);
    }
}
