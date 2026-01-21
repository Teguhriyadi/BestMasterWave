<?php

namespace App\Http\Services;

use App\Http\Mapper\DivisiRoleMapper;
use App\Http\Repositories\DivisiRoleRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DivisiRoleService
{
    public function __construct(
        protected DivisiRoleRepository $divisi_role_repository
    ) {}

    public function list()
    {
        $divisi_role = $this->divisi_role_repository->get_all_data();

        return DivisiRoleMapper::toTable($divisi_role);
    }

    public function list_users()
    {
        $divisi_role = $this->divisi_role_repository->get_users_divisi_role();

        return DivisiRoleMapper::toListData($divisi_role);
    }

    public function list_akses_role()
    {
        $divisi_role = $this->divisi_role_repository->get_akses_role();

        return DivisiRoleMapper::toListRole($divisi_role);
    }

    public function list_akses_role_all()
    {
        $divisi_role = $this->divisi_role_repository->get_akses_role_all();

        if (empty(Auth::user()->one_divisi_roles)) {
            return DivisiRoleMapper::toPermissionsRole($divisi_role);
        } else {
            return DivisiRoleMapper::toPermissionsRoleNoSuperAdmin($divisi_role);
        }
    }

    public function create(array $data): void
    {
        $this->divisi_role_repository->insertRoles(
            $data['divisi_id'],
            $data['roles'] ?? []
        );
    }

    public function getById(array $data): void
    {
        DB::transaction(function () use ($data) {
            $this->divisi_role_repository->syncRoles(
                $data['divisi_id'],
                $data['roles'] ?? []
            );
        });
    }

    public function getRolesByDivision(string $divisionId)
    {
        return $this->divisi_role_repository->getRoleIdsByDivision($divisionId);
    }
}
