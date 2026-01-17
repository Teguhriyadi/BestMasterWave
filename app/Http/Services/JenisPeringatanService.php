<?php

namespace App\Http\Services;

use App\Http\Mapper\JenisDendaMapper;
use App\Http\Mapper\JenisPeringatanMapper;
use App\Http\Repositories\JenisDendaRepository;
use App\Http\Repositories\JenisPeringatanRepository;
use Illuminate\Support\Facades\DB;

class JenisPeringatanService
{
    public function __construct(
        protected JenisPeringatanRepository $jenis_peringatan_repository
    ) {}

    public function list()
    {
        $jenis_peringatan = $this->jenis_peringatan_repository->get_all_data();

        return JenisPeringatanMapper::toTable($jenis_peringatan);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->jenis_peringatan_repository->insert_data($data);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->jenis_peringatan_repository->get_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->jenis_peringatan_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->jenis_peringatan_repository->delete_by_id($id);
        });
    }
}
