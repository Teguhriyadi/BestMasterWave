<?php

namespace App\Http\Services;

use App\Http\Mapper\LokasiMapper;
use App\Http\Repositories\LokasiRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LokasiService
{
    public function __construct(
        protected LokasiRepository $lokasi_repository
    ) {}

    public function list()
    {
        $lokasi = $this->lokasi_repository->get_all_data();

        return LokasiMapper::toTable($lokasi);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->lokasi_repository->insert_data($data);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->lokasi_repository->get_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->lokasi_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->lokasi_repository->delete_by_id($id);
        });
    }
}
