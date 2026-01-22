<?php

use App\Http\Controllers\Transaction\Shopee\PesananController;
use Illuminate\Support\Facades\Route;

Route::prefix("shopee-pesanan")->group(function() {
    Route::get("/", [PesananController::class, "index"])->middleware("permission:shopee-pesanan.create");
    Route::post("/", [PesananController::class, "store"])->middleware("permission:shopee-pesanan.create");
    Route::post("/process", [PesananController::class, "process"])->middleware("permission:shopee-pesanan.create");
    Route::get("/{id}/show", [PesananController::class, "show"])->middleware("permission:shopee-pesanan.show");
    Route::get("/preview", [PesananController::class, "preview"])->middleware("permission:shopee-pesanan.edit");
    Route::get("/preview/{upload}", [PesananController::class, "previewData"])->middleware("permission:shopee-pesanan.show");
    Route::post("/{id}/process-database", [PesananController::class, "processDatabase"])->middleware("permission:shopee-pesanan.edit");
    Route::prefix("data")->group(function() {
        Route::get("/", [PesananController::class, "kelola"])->middleware("permission:shopee-pesanan.read");
        Route::get("/{uuid}/detail", [PesananController::class, "detail"])->middleware("permission:shopee-pesanan.show");
    });
});
