<?php

use App\Http\Controllers\Rekap\KetidakHadiranController;
use App\Http\Controllers\Rekap\LogAbsensiController;
use Illuminate\Support\Facades\Route;

Route::prefix("ketidakhadiran")->group(function() {
    Route::get("/", [KetidakHadiranController::class, "index"]);
    Route::post("/", [KetidakHadiranController::class, "store"]);
    Route::get("/{id}/edit", [KetidakHadiranController::class, "edit"]);
    Route::put("/{id}", [KetidakHadiranController::class, "update"]);
    Route::delete("/{id}", [KetidakHadiranController::class, "destroy"]);
});
