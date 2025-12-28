<?php

namespace App\Http\Services;

use App\Http\Repositories\RoleRepository;

class RoleService
{
    public function __construct(
        protected RoleRepository $role
    ) {}

    public function get_all_data()
    {
        return $this->role->all_data();
    }

    public function create(array $data)
    {
        return $this->role->store($data);
    }
}
