<?php

use App\Http\Controllers\Transaction\Shopee\HargaModalController;
use Illuminate\Support\Facades\Route;

Route::prefix("shopee-harga-modal")->group(function() {
    Route::get("/", [HargaModalController::class, "index"]);
    Route::get("/get-harga", [HargaModalController::class, "get_harga_modal"]);
    Route::put("/", [HargaModalController::class, "update"]);
});
