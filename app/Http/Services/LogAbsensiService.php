<?php

namespace App\Http\Services;

use App\Http\Mapper\BarangMapper;
use App\Http\Mapper\LogAbsensiMapper;
use App\Http\Repositories\LogAbsensiRepository;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class LogAbsensiService
{
    public function __construct(
        protected LogAbsensiRepository $log_absensi_repository
    ) {}

    public function list()
    {
        $barang = $this->log_absensi_repository->get_all_data();

        return $barang;
    }

    public function edit(string $id)
    {
        return DB::transaction(function () use ($id) {
            return $this->log_absensi_repository->get_data_by_id($id);
        });
    }


    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->log_absensi_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->log_absensi_repository->delete_by_id($id);
        });
    }
}
