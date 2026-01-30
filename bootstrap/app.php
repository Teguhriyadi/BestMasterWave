<?php

use App\Http\Middleware\CheckPermissionMiddleware;
use App\Http\Middleware\IsAutentikasiMiddleware;
use App\Http\Middleware\RedirectIfAuthenticatedMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function() {
            Route::middleware(["web", "guest"])->group(function() {
                require __DIR__ . '/../routes/authentication/login.php';
            });
            Route::middleware(["web", "autentikasi"])->group(function() {
                Route::get("/", function() {
                    return redirect()->to("/admin-panel/dashboard");
                });
                Route::prefix("admin-panel")->group(function() {
                    require __DIR__ . '/../routes/app/dashboard.php';
                    require __DIR__ . '/../routes/master/platform.php';
                    require __DIR__ . '/../routes/master/seller.php';
                    require __DIR__ . '/../routes/transaction/shopee/pendapatan.php';
                    require __DIR__ . '/../routes/transaction/shopee/pesanan.php';
                    require __DIR__ . '/../routes/master/role.php';
                    require __DIR__ . '/../routes/master/supplier.php';
                    require __DIR__ . '/../routes/master/bank.php';
                    require __DIR__ . '/../routes/master/barang.php';
                    require __DIR__ . '/../routes/transaction/invoice/pembelian.php';
                    require __DIR__ . '/../routes/master/divisi.php';
                    require __DIR__ . '/../routes/master/divisi-role.php';
                    require __DIR__ . '/../routes/master/users.php';
                    require __DIR__ . '/../routes/master/jabatan.php';
                    require __DIR__ . '/../routes/master/karyawan.php';
                    require __DIR__ . '/../routes/pengaturan/profil-saya.php';
                    require __DIR__ . '/../routes/kelola-menu/menu.php';
                    require __DIR__ . '/../routes/kelola-menu/permissions.php';
                    require __DIR__ . '/../routes/kelola-menu/role-permissions.php';
                    require __DIR__ . '/../routes/master/lokasi.php';
                    require __DIR__ . '/../routes/rekap/absensi.php';
                    require __DIR__ . '/../routes/rekap/ketidakhadiran.php';
                    require __DIR__ . '/../routes/master/jenis-denda.php';
                    require __DIR__ . '/../routes/rekap/denda.php';
                    require __DIR__ . '/../routes/master/jenis-peringatan.php';
                    require __DIR__ . '/../routes/rekap/peringatan.php';
                    require __DIR__ . '/../routes/keuangan/kasbon.php';
                    require __DIR__ . '/../routes/master/setup-jam-kerja.php';
                    require __DIR__ . '/../routes/master/paket.php';
                    require __DIR__ . '/../routes/transaction/tiktok/pendapatan.php';
                    require __DIR__ . '/../routes/transaction/tiktok/pesanan.php';
                    require __DIR__ . '/../routes/transaction/shopee/selisih-ongkir.php';
                    require __DIR__ . '/../routes/transaction/tiktok/selisih-ongkir.php';
                    require __DIR__ . '/../routes/transaction/shopee/laporan.php';
                    require __DIR__ . '/../routes/transaction/tiktok/laporan.php';
                    require __DIR__ . '/../routes/transaction/shopee/harga-modal.php';
                    require __DIR__ . '/../routes/transaction/tiktok/harga-modal.php';
                });
            });
        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            "autentikasi" => IsAutentikasiMiddleware::class,
            "guest" => RedirectIfAuthenticatedMiddleware::class,
            "permission" => CheckPermissionMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
