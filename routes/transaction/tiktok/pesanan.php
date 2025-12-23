<?php

use App\Http\Controllers\Transaction\PesananController;
use Illuminate\Support\Facades\Route;

Route::prefix("pesanan")->group(function() {
    Route::get("/", [PesananController::class, "index"]);
    Route::post("/", [PesananController::class, "store"]);
    Route::post("/process", [PesananController::class, "process"]);
    Route::get("/preview", [PesananController::class, "preview"]);
    Route::get("/preview/{upload}", [PesananController::class, "previewData"]);
});
