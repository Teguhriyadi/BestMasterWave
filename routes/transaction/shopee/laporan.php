<?php

use App\Http\Controllers\Transaction\Shopee\LaporanController;
use Illuminate\Support\Facades\Route;

Route::prefix("shopee-laporan")->group(function() {
    Route::get("/", [LaporanController::class, "index"]);
});
