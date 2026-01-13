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

        $roleId   = optional($user->one_divisi_roles)->role_id;
        $divisiId = AuthDivisi::id();

        $allowed = RolePermission::where('role_id', $roleId)
            ->where('divisi_id', $divisiId)
            ->whereHas('permission', fn ($q) => $q->where('akses', $permission))
            ->exists();

        if (!$allowed) {
            abort(403, 'Anda tidak memiliki akses');
        }

        return $next($request);
    }
}
