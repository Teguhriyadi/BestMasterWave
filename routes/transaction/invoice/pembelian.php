<?php

use App\Http\Controllers\Transaction\Invoice\PembelianController;
use Illuminate\Support\Facades\Route;

Route::prefix("pembelian")->group(function() {
    Route::get("/", [PembelianController::class, "index"]);
    Route::get("/create", [PembelianController::class, "create"]);
    Route::post("/", [PembelianController::class, "store"]);
    Route::get("/{id}/edit", [PembelianController::class, "edit"]);
    Route::get("/{id}/detail", [PembelianController::class, "detail"]);
    Route::put("/{id}", [PembelianController::class, "update"]);
    Route::delete("/{id}", [PembelianController::class, "destroy"]);
});
