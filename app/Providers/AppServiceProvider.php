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

        Carbon::setLocale('id');

        View::composer('*', function ($view) {

            if (!Auth::check()) {
                return;
            }

            $roleId   = optional(Auth::user()->one_divisi_roles)->role_id;
            $divisiId = AuthDivisi::id();

            if (!$roleId || !$divisiId) {
                $view->with('sidebarMenus', collect());
                return;
            }

            $menuService = app(MenuService::class);
            $sidebarMenus = $menuService->sidebar($roleId, $divisiId);

            $view->with('sidebarMenus', $sidebarMenus);
        });
    }
}
