<?php

namespace App\Http\Services;

use App\Http\Mapper\RolePermissionsMapper;
use App\Http\Repositories\RolePermissionsRepository;

class RolePermissionsService
{
    public function __construct(
        protected RolePermissionsRepository $role_permissions_repository
    ) {}

    public function listPermissions()
    {
        $permissions = $this->role_permissions_repository->list_permissions();

        return RolePermissionsMapper::toTable($permissions);
    }

    public function getSelectedPermissions(
        string $roleId,
        ?string $divisiId = null
    ): array {
        return $this->role_permissions_repository->getSelectedPermissions(
            $roleId,
            $divisiId
        );
    }

    public function deleteByRoleAndMenu(
        string $roleId,
        string $menuId,
        ?string $divisiId = null
    ): void {
        $this->role_permissions_repository->deleteByRoleAndMenu(
            $roleId,
            $menuId,
            $divisiId
        );
    }

    public function savePermissions(
        string $roleId,
        array $permissionIds,
        ?string $divisiId = null
    ): void {
        $this->role_permissions_repository->sync_permissions($roleId, $permissionIds, $divisiId);
    }
}
