<?php

use App\Http\Controllers\Rekap\LogAbsensiController;
use Illuminate\Support\Facades\Route;

Route::prefix("absensi")->group(function() {
    Route::get("/", [LogAbsensiController::class, "index"])->middleware("permission:absensi.read");
    Route::get("/create", [LogAbsensiController::class, "create"])->middleware("permission:absensi.create");
    Route::post("/", [LogAbsensiController::class, "store"])->middleware("permission:absensi.create");
    Route::get("/{id}/edit", [LogAbsensiController::class, "edit"])->middleware("permission:absensi.edit");
    Route::put("/{id}", [LogAbsensiController::class, "update"])->middleware("permission:absensi.edit");
    Route::delete("/{id}", [LogAbsensiController::class, "destroy"])->middleware("permission:absensi.delete");
});
