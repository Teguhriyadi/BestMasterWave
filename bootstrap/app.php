<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function() {
            Route::middleware("web")->group(function() {
                Route::get("/", function() {
                    return redirect()->to("/admin-panel/dashboard");
                });
                Route::prefix("admin-panel")->group(function() {
                    require __DIR__ . '/../routes/app/dashboard.php';
                    require __DIR__ . '/../routes/master/platform.php';
                    require __DIR__ . '/../routes/master/seller.php';
                    require __DIR__ . '/../routes/transaction/shopee/pendapatan.php';
                    require __DIR__ . '/../routes/transaction/shopee/pesanan.php';
                });
            });
        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
