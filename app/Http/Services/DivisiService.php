<?php

namespace App\Http\Services;

use App\Http\Mapper\DivisiMapper;
use App\Http\Repositories\DivisiRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DivisiService
{
    public function __construct(
        protected DivisiRepository $divisi_repository
    ) {}

    public function list()
    {
        $divisi = $this->divisi_repository->get_all_data();

        return DivisiMapper::toTable($divisi);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->divisi_repository->insert_data($data);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->divisi_repository->get_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->divisi_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->divisi_repository->delete_by_id($id);
        });
    }

    public function getRolesByDivision(string $divisionId)
    {
        if (empty(Auth::user()->one_divisi_roles)) {
            return collect([$this->divisi_repository->getSuperAdmin()])
                ->filter();
        }

        return $this->divisi_repository
            ->getByDivision($divisionId)
            ->where('nama_role', '!=', 'Super Admin')
            ->values();
    }
}
