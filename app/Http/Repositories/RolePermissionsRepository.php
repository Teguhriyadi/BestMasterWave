<?php

namespace App\Http\Repositories;

use App\Models\RolePermission;
use Illuminate\Support\Facades\DB;

class RolePermissionsRepository
{
    public function list_permissions()
    {
        return RolePermission::with([
            'role',
            'permission.menu'
        ])->get();
    }

    public function getSelectedPermissions(
        string $roleId,
        ?string $divisiId = null
    ): array {
        return RolePermission::where('role_id', $roleId)
            ->when($divisiId, fn ($q) => $q->where('divisi_id', $divisiId))
            ->pluck('permission_id')
            ->toArray();
    }

    public function deleteByRoleAndMenu(
        string $roleId,
        string $menuId,
        ?string $divisiId = null
    ): void {
        RolePermission::where('role_id', $roleId)
            ->whereHas('permission', fn ($q) =>
                $q->where('menu_id', $menuId)
            )
            ->when($divisiId, fn ($q) => $q->where('divisi_id', $divisiId))
            ->delete();
    }


    public function sync_permissions(
        string $roleId,
        array $permissionIds,
        ?string $divisiId = null
    ): void {
        DB::transaction(function () use ($roleId, $permissionIds, $divisiId) {

            RolePermission::where('role_id', $roleId)
                ->when($divisiId, fn ($q) => $q->where('divisi_id', $divisiId))
                ->delete();

            foreach ($permissionIds as $permissionId) {
                RolePermission::create([
                    'role_id'       => $roleId,
                    'permission_id' => $permissionId,
                    'divisi_id'     => $divisiId,
                ]);
            }
        });
    }
}
