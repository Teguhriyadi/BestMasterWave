<?php

namespace App\Http\Services;

use App\Http\Mapper\DivisiMapper;
use App\Http\Mapper\JabatanMapper;
use App\Http\Repositories\JabatanRepository;
use Illuminate\Support\Facades\DB;

class JabatanService
{
    public function __construct(
        protected JabatanRepository $jabatan_repository
    ) {}

    public function list()
    {
        $jabatan = $this->jabatan_repository->get_all_data();

        return JabatanMapper::toTable($jabatan);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->jabatan_repository->insert_data($data);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->jabatan_repository->get_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->jabatan_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->jabatan_repository->delete_by_id($id);
        });
    }
}
