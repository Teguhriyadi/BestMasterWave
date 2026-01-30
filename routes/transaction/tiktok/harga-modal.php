<?php

use App\Http\Controllers\Transaction\Tiktok\HargaModalController;
use Illuminate\Support\Facades\Route;

Route::prefix("tiktok-harga-modal")->group(function() {
    Route::get("/", [HargaModalController::class, "index"])->middleware("permission:tiktok-harga-modal.read");
    Route::put("/", [HargaModalController::class, "update"])->middleware("permission:tiktok-harga-modal.edit");
});
