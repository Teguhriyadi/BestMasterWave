<?php

namespace App\Http\Services;

use App\Http\Mapper\SetupJamKerjaMapper;
use App\Http\Repositories\SetupJamKerjaRepository;
use Illuminate\Support\Facades\DB;

class SetupJamKerjaService
{
    public function __construct(
        protected SetupJamKerjaRepository $setup_jam_kerja_repository
    ) {}

    public function list()
    {
        $setup_jam_kerja = $this->setup_jam_kerja_repository->get_all_data();

        return SetupJamKerjaMapper::toTable($setup_jam_kerja);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->setup_jam_kerja_repository->insert_data($data);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->setup_jam_kerja_repository->get_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->setup_jam_kerja_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->setup_jam_kerja_repository->delete_by_id($id);
        });
    }
}
