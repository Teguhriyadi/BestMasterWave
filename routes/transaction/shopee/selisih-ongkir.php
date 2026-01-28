<?php

use App\Http\Controllers\Transaction\Shopee\SelisihOngkirController;
use Illuminate\Support\Facades\Route;

Route::prefix("shopee-selisih-ongkir")->group(function() {
    Route::get("/", [SelisihOngkirController::class, "index"])->middleware("permission:shopee-selisih-ongkir.read");
    Route::get("/download", [SelisihOngkirController::class, "download"])->middleware("permission:shopee-selisih-ongkir.read");
});
