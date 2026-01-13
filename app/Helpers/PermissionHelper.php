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

        $roleId   = optional($user->one_divisi_roles)->role_id;
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
