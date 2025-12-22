<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function() {
            Route::middleware("web")->prefix("admin-panel")->group(function() {
                require __DIR__ . '/../routes/app/dashboard.php';
                require __DIR__ . '/../routes/master/platform.php';
                require __DIR__ . '/../routes/master/seller.php';
                require __DIR__ . '/../routes/transaction/pendapatan.php';
                require __DIR__ . '/../routes/transaction/pesanan.php';
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
