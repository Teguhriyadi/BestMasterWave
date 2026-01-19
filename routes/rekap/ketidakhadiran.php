<?php

use App\Http\Controllers\Rekap\KetidakHadiranController;
use Illuminate\Support\Facades\Route;

Route::prefix("ketidakhadiran")->group(function() {
    Route::get("/", [KetidakHadiranController::class, "index"])->middleware("permission:ketidakhadiran.read");
    Route::post("/", [KetidakHadiranController::class, "store"])->middleware("permission:ketidakhadiran.create");
    Route::get("/{id}/edit", [KetidakHadiranController::class, "edit"])->middleware("permission:ketidakhadiran.edit");
    Route::put("/{id}", [KetidakHadiranController::class, "update"])->middleware("permission:ketidakhadiran.edit");
    Route::delete("/{id}", [KetidakHadiranController::class, "destroy"])->middleware("permission:ketidakhadiran.delete");
    Route::get("/{id}/ubah-status", [KetidakHadiranController::class, "ubah_status"])->middleware("permission:ketidakhadiran.change_status");
    Route::put("/{id}/ubah-status", [KetidakHadiranController::class, "update_status_terbaru"])->middleware("permission:ketidakhadiran.change_status");
});
