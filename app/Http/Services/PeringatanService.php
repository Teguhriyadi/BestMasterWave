<?php

namespace App\Http\Services;

use App\Http\Mapper\PeringatanMapper;
use App\Http\Repositories\PeringatanRepository;
use Illuminate\Support\Facades\DB;

class PeringatanService
{
    public function __construct(
        protected PeringatanRepository $peringatan_repository
    ) {}

    public function list()
    {
        $peringatan = $this->peringatan_repository->get_all_data();

        return PeringatanMapper::toTable($peringatan);
    }

    public function list_peringatan()
    {
        $peringatan = $this->peringatan_repository->get_all_data();

        return PeringatanMapper::toTable($peringatan);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->peringatan_repository->insert_data($data);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->peringatan_repository->get_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->peringatan_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->peringatan_repository->delete_by_id($id);
        });
    }
}
