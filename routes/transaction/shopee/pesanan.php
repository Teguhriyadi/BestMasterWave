<?php

use App\Http\Controllers\Transaction\Shopee\PesananController;
use Illuminate\Support\Facades\Route;

Route::prefix("shopee/pesanan")->group(function() {
    Route::get("/", [PesananController::class, "index"]);
    Route::post("/", [PesananController::class, "store"]);
    Route::post("/process", [PesananController::class, "process"]);
    Route::get("/{id}/show", [PesananController::class, "show"]);
    Route::get("/preview", [PesananController::class, "preview"]);
    Route::get("/preview/{upload}", [PesananController::class, "previewData"]);
    Route::post("/{id}/process-database", [PesananController::class, "processDatabase"]);
});
