<?php

use App\Http\Controllers\Transaction\Tiktok\LaporanController;
use Illuminate\Support\Facades\Route;

Route::prefix("tiktok-laporan")->group(function() {
    Route::get("/", [LaporanController::class, "index"])->middleware("permission:tiktok-laporan.read");
});
