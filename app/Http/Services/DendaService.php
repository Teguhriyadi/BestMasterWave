<?php

namespace App\Http\Services;

use App\Http\Mapper\DendaMapper;
use App\Http\Repositories\DendaRepository;
use Illuminate\Support\Facades\DB;

class DendaService
{
    public function __construct(
        protected DendaRepository $denda_repository
    ) {}

    public function list()
    {
        $denda = $this->denda_repository->get_all_data();

        return DendaMapper::toTable($denda);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->denda_repository->insert_data($data);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->denda_repository->get_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->denda_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->denda_repository->delete_by_id($id);
        });
    }

    public function getRolesByDivision(string $divisionId)
    {
        return $this->divisi_repository->getRolesByDivisi($divisionId);
    }
}
