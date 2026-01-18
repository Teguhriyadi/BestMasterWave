<?php

use App\Http\Controllers\Master\LokasiController;
use Illuminate\Support\Facades\Route;

Route::prefix("lokasi")->group(function() {
    Route::get("/", [LokasiController::class, "index"])->middleware("permission:lokasi.read");
    Route::post("/", [LokasiController::class, "store"])->middleware("permission:lokasi.create");
    Route::get("/{id}/edit", [LokasiController::class, "edit"])->middleware("permission:lokasi.edit");
    Route::put("/{id}", [LokasiController::class, "update"])->middleware("permission:lokasi.edit");
    Route::delete("/{id}", [LokasiController::class, "destroy"])->middleware("permission:lokasi.delete");
});
