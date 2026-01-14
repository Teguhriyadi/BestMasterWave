<?php

use App\Http\Controllers\Master\BarangController;
use Illuminate\Support\Facades\Route;

Route::prefix("barang")->group(function() {
    Route::get("/", [BarangController::class, "index"])->middleware("permission:barang.read");
    Route::post("/", [BarangController::class, "store"])->middleware("permission:barang.create");
    Route::get("/{id}/edit", [BarangController::class, "edit"])->middleware("permission:barang.edit");
    Route::put("/{id}", [BarangController::class, "update"])->middleware("permission:barang.edit");
    Route::delete("/{id}", [BarangController::class, "destroy"])->middleware("permission:barang.delete");
    Route::get("/upload", [BarangController::class, "upload"]);
    Route::post("/upload", [BarangController::class, "process_upload"]);
});
