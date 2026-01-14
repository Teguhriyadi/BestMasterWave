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

        if ($user->isSuperAdminGlobal() || $user->isSuperAdminCabang()) {
            return true;
        }

        $userDivisiRole = $user->one_divisi_roles;

        if (! $userDivisiRole) {
            return false;
        }

        return RolePermission::where('role_id', $userDivisiRole->role_id)
            ->where(function ($q) use ($userDivisiRole) {
                $q->where('divisi_id', $userDivisiRole->divisi_id)
                    ->orWhereNull('divisi_id');
            })
            ->whereHas(
                'permission',
                fn($q) =>
                $q->where('akses', $permission)
            )
            ->exists();
    }
}
