<?php

namespace App\Http\Services;

use App\Http\Mapper\KetidakHadiranMapper;
use App\Http\Repositories\KetidakHadiranRepository;
use Illuminate\Support\Facades\DB;

class KetidakHadiranService
{
    public function __construct(
        protected KetidakHadiranRepository $ketidak_hadiran_repository
    ) {}

    public function list()
    {
        $ketidakhadiran = $this->ketidak_hadiran_repository->get_all_data();

        return KetidakHadiranMapper::toTable($ketidakhadiran);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->ketidak_hadiran_repository->insert_data($data);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->ketidak_hadiran_repository->get_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->ketidak_hadiran_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->ketidak_hadiran_repository->delete_by_id($id);
        });
    }
}
