<?php

namespace App\Http\Services;

use App\Http\Mapper\PermissionMapper;
use App\Http\Repositories\PermissionsRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionsService
{
    public function __construct(
        protected PermissionsRepository $permissions_repository
    ) {}

    public function list()
    {
        $supplier = $this->permissions_repository->get_all_data();

        return PermissionMapper::toTable($supplier);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->permissions_repository->insert_data($data);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->permissions_repository->get_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->permissions_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->permissions_repository->delete_by_id($id);
        });
    }
}
