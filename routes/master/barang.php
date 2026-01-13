<?php

use App\Http\Controllers\Master\BarangController;
use Illuminate\Support\Facades\Route;

Route::prefix("barang")->group(function() {
    Route::get("/", [BarangController::class, "index"]);
    Route::post("/", [BarangController::class, "store"]);
    Route::get("/{id}/edit", [BarangController::class, "edit"]);
    Route::put("/{id}", [BarangController::class, "update"]);
    Route::delete("/{id}", [BarangController::class, "destroy"]);
    Route::get("/upload", [BarangController::class, "upload"]);
    Route::post("/upload", [BarangController::class, "process_upload"]);
});
