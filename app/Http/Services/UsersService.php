<?php

namespace App\Http\Services;

use App\Http\Mapper\DivisiMapper;
use App\Http\Mapper\UsersMapper;
use App\Http\Repositories\DivisiRepository;
use App\Http\Repositories\UsersRepository;
use Illuminate\Support\Facades\DB;

class UsersService
{
    public function __construct(
        protected UsersRepository $users_repository
    ) {}

    public function list()
    {
        $divisi = $this->users_repository->get_all_data();

        return UsersMapper::toTable($divisi);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->users_repository->insert_data($data);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->users_repository->get_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->users_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->users_repository->delete_by_id($id);
        });
    }
}
