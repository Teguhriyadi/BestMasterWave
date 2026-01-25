<?php

use App\Http\Controllers\Transaction\Tiktok\PesananController;
use Illuminate\Support\Facades\Route;

Route::prefix("tiktok-pesanan")->group(function() {
    Route::get("/", [PesananController::class, "index"])->middleware("permission:tiktok-pesanan.create");
    Route::post("/", [PesananController::class, "store"])->middleware("permission:tiktok-pesanan.create");
    Route::post("/process", [PesananController::class, "process"])->middleware("permission:tiktok-pesanan.create");
    Route::get("/{id}/show", [PesananController::class, "show"])->middleware("permission:tiktok-pesanan.show");
    Route::get("/preview/{upload}", [PesananController::class, "previewData"])->middleware("permission:tiktok-pesanan.show");
    Route::post("/{id}/process-database", [PesananController::class, "processDatabase"])->middleware("permission:tiktok-pesanan.edit");
    Route::prefix("data")->group(function() {
        Route::get("/", [PesananController::class, "kelola"])->middleware("permission:tiktok-pesanan.read");
        Route::get("/{uuid}/detail", [PesananController::class, "detail"])->middleware("permission:tiktok-pesanan.show");
    });
});
