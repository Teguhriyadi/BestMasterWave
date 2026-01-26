<?php

use App\Http\Controllers\Transaction\Shopee\PendapatanController;
use App\Http\Controllers\Transaction\Shopee\SelisihOngkirController;
use Illuminate\Support\Facades\Route;

Route::prefix("shopee-selisih-ongkir")->group(function() {
    Route::get("/", [SelisihOngkirController::class, "index"]);
});
