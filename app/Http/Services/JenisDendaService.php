<?php

namespace App\Http\Services;

use App\Http\Mapper\JenisDendaMapper;
use App\Http\Repositories\JenisDendaRepository;
use Illuminate\Support\Facades\DB;

class JenisDendaService
{
    public function __construct(
        protected JenisDendaRepository $jenis_denda_repository
    ) {}

    public function list()
    {
        $jenis_denda = $this->jenis_denda_repository->get_all_data();

        return JenisDendaMapper::toTable($jenis_denda);
    }

    public function list_denda()
    {
        $jenis_denda = $this->jenis_denda_repository->get_all_data();

        return JenisDendaMapper::toListDenda($jenis_denda);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->jenis_denda_repository->insert_data($data);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->jenis_denda_repository->get_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->jenis_denda_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->jenis_denda_repository->delete_by_id($id);
        });
    }
}
