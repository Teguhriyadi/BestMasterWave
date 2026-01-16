<?php

use App\Http\Controllers\Master\LokasiController;
use Illuminate\Support\Facades\Route;

Route::prefix("lokasi")->group(function() {
    Route::get("/", [LokasiController::class, "index"]);
    Route::post("/", [LokasiController::class, "store"]);
    Route::get("/{id}/edit", [LokasiController::class, "edit"]);
    Route::put("/{id}", [LokasiController::class, "update"]);
    Route::delete("/{id}", [LokasiController::class, "destroy"]);
});
