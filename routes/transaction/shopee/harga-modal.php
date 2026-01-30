<?php

use App\Http\Controllers\Transaction\Shopee\HargaModalController;
use Illuminate\Support\Facades\Route;

Route::prefix("shopee-harga-modal")->group(function() {
    Route::get("/", [HargaModalController::class, "index"])->middleware("permission:shopee-harga-modal.read");
    Route::put("/", [HargaModalController::class, "update"])->middleware("permission:shopee-harga-modal.update");
});
