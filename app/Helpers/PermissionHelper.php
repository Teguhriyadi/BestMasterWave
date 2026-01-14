<?php

use App\Helpers\AuthDivisi;
use Illuminate\Support\Facades\Auth;
use App\Models\RolePermission;

if (! function_exists('canPermission')) {
    function canPermission(string $permission): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        $role = optional($user->one_divisi_roles)->roles;

        if ($role && $role->nama_role === 'Super Admin') {
            return true;
        }

        $roleId   = $role->id ?? null;
        $divisiId = AuthDivisi::id();

        if (! $roleId || ! $divisiId) {
            return false;
        }

        return RolePermission::where('role_id', $roleId)
            ->where('divisi_id', $divisiId)
            ->whereHas('permission', fn ($q) => $q->where('akses', $permission))
            ->exists();
    }
}
