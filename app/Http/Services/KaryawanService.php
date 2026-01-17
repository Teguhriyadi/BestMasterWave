<?php

namespace App\Http\Services;

use App\Http\Mapper\DivisiMapper;
use App\Http\Mapper\KaryawanMapper;
use App\Http\Repositories\DivisiRepository;
use App\Http\Repositories\KaryawanRepository;
use Illuminate\Support\Facades\DB;

class KaryawanService
{
    public function __construct(
        protected KaryawanRepository $karyawan_repository,
        protected DivisiRepository $divisi_repository
    ) {}

    public function list()
    {
        $divisi = $this->karyawan_repository->get_all_data();

        return KaryawanMapper::toTable($divisi);
    }

    public function list_karyawan()
    {
        $karyawan = $this->karyawan_repository->get_list_karyawan();

        return KaryawanMapper::toListKaryawan($karyawan);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->karyawan_repository->insert_data($data);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->karyawan_repository->get_data_by_id($id);
        });
    }

    public function show_log(string $id)
    {
        return DB::transaction(function() use ($id) {
            $log = $this->karyawan_repository->get_log_karyawan($id);

            return KaryawanMapper::toListLogKaryawan($log);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->karyawan_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->karyawan_repository->delete_by_id($id);
        });
    }

    public function getRolesByDivision(string $divisionId)
    {
        return $this->divisi_repository->getRolesByDivisi($divisionId);
    }
}
