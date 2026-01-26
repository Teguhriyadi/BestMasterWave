<?php

use App\Http\Controllers\Transaction\Tiktok\SelisihOngkirController;
use Illuminate\Support\Facades\Route;

Route::prefix("tiktok-selisih-ongkir")->group(function() {
    Route::get("/", [SelisihOngkirController::class, "index"])->middleware("permission:tiktok-selisih-ongkir.read");
});
