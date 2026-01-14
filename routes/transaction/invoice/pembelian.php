<?php

use App\Http\Controllers\Transaction\Invoice\PembelianController;
use Illuminate\Support\Facades\Route;

Route::prefix("pembelian")->group(function() {
    Route::get("/", [PembelianController::class, "index"])->middleware("permission:pembelian.read");
    Route::get("/create", [PembelianController::class, "create"])->middleware("permission:pembelian.create");
    Route::post("/", [PembelianController::class, "store"])->middleware("permission:pembelian.create");
    Route::get("/{id}/edit", [PembelianController::class, "edit"])->middleware("permission:pembelian.edit");
    Route::get("/{id}/detail", [PembelianController::class, "detail"])->middleware("permission:pembelian.show");
    Route::put("/{id}", [PembelianController::class, "update"])->middleware("permission:pembelian.edit");
    Route::delete("/{id}", [PembelianController::class, "destroy"])->middleware("permission:pembelian.delete");
});
