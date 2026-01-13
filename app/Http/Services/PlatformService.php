<?php

namespace App\Http\Services;

use App\Http\Mapper\PlatformMapper;
use App\Http\Repositories\PlatformRepository;
use Illuminate\Support\Facades\DB;

class PlatformService
{
    public function __construct(
        protected PlatformRepository $platform_repository
    ) {}

    public function list()
    {
        $platform = $this->platform_repository->get_all_data();

        return PlatformMapper::toTable($platform);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->platform_repository->insert_data($data);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->platform_repository->get_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->platform_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->platform_repository->delete_by_id($id);
        });
    }
}
