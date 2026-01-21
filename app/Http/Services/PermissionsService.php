<?php

namespace App\Http\Services;

use App\Http\Mapper\PermissionMapper;
use App\Http\Repositories\PermissionsRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionsService
{
    public function __construct(
        protected PermissionsRepository $permissions_repository
    ) {}

    public function list()
    {
        $supplier = $this->permissions_repository->get_all_data();

        return PermissionMapper::toTable($supplier);
    }

    public function updateGroup(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $row = $this->permissions_repository->get_data_by_id($id);

            $aksesBaseOld = explode('.', $row->akses)[0];

            $this->permissions_repository->delete_group_data(
                $row->nama,
                $aksesBaseOld,
                $row->menu_id
            );

            $rows = [];

            foreach ($data['tipe_akses'] as $tipe) {
                $rows[] = [
                    'id'      => Str::uuid(),
                    'nama'    => $data['nama'],
                    'akses'   => $data['akses'] . '.' . $tipe,
                    'menu_id' => $data['menu_id'],
                ];
            }

            return $this->permissions_repository->insert_data($rows);
        });
    }

    public function getSingle(string $id)
    {
        return $this->permissions_repository->get_data_by_id($id);
    }

    public function getGroup(string $nama, string $aksesBase, string $menuId)
    {
        return $this->permissions_repository
            ->get_group_data($nama, $aksesBase, $menuId);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            $rows = [];

            foreach ($data['tipe_akses'] as $tipe) {
                $rows[] = [
                    'id'      => Str::uuid(),
                    'nama'    => $data['nama'],
                    'akses'   => $data['akses'] . '.' . $tipe,
                    'menu_id' => $data['menu_id'],
                ];
            }

            return $this->permissions_repository->insert_data($rows);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function () use ($id) {
            return $this->permissions_repository->get_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->permissions_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {

        $row = $this->permissions_repository->get_data_by_id($id);

        if (!$row) {
            throw new \Exception("Data permission tidak ditemukan");
        }

        $aksesBase = explode('.', $row->akses)[0];

        return $this->permissions_repository->delete_group_data(
            $row->nama,
            $aksesBase,
            $row->menu_id
        );
    });
    }
}
