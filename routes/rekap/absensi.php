<?php

use App\Http\Controllers\Rekap\LogAbsensiController;
use Illuminate\Support\Facades\Route;

Route::prefix("absensi")->group(function() {
    Route::get("/", [LogAbsensiController::class, "index"]);
    Route::get("/create", [LogAbsensiController::class, "create"]);
    Route::post("/", [LogAbsensiController::class, "store"]);
    Route::get("/{id}/edit", [LogAbsensiController::class, "edit"]);
    Route::put("/{id}", [LogAbsensiController::class, "update"]);
    Route::delete("/{id}", [LogAbsensiController::class, "destroy"]);
});
