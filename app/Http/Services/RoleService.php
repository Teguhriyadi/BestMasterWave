<?php

namespace App\Http\Services;

use App\Http\Mapper\RoleMapper;
use App\Http\Repositories\RoleRepository;
use Illuminate\Support\Facades\DB;

class RoleService
{
    public function __construct(
        protected RoleRepository $role_repository
    ) {}

    public function list()
    {
        $supplier = $this->role_repository->get_all_data();

        return RoleMapper::toTable($supplier);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->role_repository->insert_data($data);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->role_repository->get_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->role_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->role_repository->delete_by_id($id);
        });
    }
}
