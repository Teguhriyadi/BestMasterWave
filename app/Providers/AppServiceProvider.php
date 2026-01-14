<?php

namespace App\Providers;

use App\Helpers\AuthDivisi;
use App\Http\Services\MenuService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        require_once app_path('Helpers/MenuHelper.php');
        require_once app_path('Helpers/PermissionHelper.php');

        Carbon::setLocale('id');

        View::composer('*', function ($view) {

            if (!Auth::check()) {
                $view->with('sidebarMenus', collect());
                return;
            }

            $role = optional(Auth::user()->one_divisi_roles)->role;
            $isSuperAdmin = $role && $role->nama_role === 'Super Admin';

            $menuService = app(\App\Http\Services\MenuService::class);
            $sidebarMenus = $menuService->sidebar($isSuperAdmin);

            $view->with('sidebarMenus', $sidebarMenus);
        });
    }
}
