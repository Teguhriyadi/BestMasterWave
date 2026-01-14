<?php

namespace App\Http\Middleware;

use App\Helpers\AuthDivisi;
use App\Models\RolePermission;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        if ($user->isSuperAdminGlobal() || $user->isSuperAdminCabang()) {
            return $next($request);
        }

        $userDivisiRole = $user->one_divisi_roles;

        if (!$userDivisiRole) {
            abort(403, 'Role cabang tidak ditemukan');
        }

        $roleId   = $userDivisiRole->role_id;
        $divisiId = $userDivisiRole->divisi_id;

        if (!$divisiId) {
            abort(403, 'Divisi tidak valid');
        }

        $allowed = RolePermission::where('role_id', $roleId)
            ->where(function ($q) use ($divisiId) {
                $q->where('divisi_id', $divisiId)
                    ->orWhereNull('divisi_id');
            })
            ->whereHas(
                'permission',
                fn($q) =>
                $q->where('akses', $permission)
            )
            ->exists();

        if (!$allowed) {
            abort(403, 'Anda tidak memiliki akses');
        }

        return $next($request);
    }
}
