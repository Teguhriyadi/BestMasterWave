<?php

use App\Http\Controllers\Master\PeringatanKaryawanController;
use Illuminate\Support\Facades\Route;

Route::prefix("peringatan")->group(function() {
    Route::get("/", [PeringatanKaryawanController::class, "index"])->middleware("permission:peringatan.read");
    Route::get("/create", [PeringatanKaryawanController::class, "create"])->middleware("permission:peringatan.create");
    Route::post("/", [PeringatanKaryawanController::class, "store"])->middleware("permission:peringatan.create");
    Route::get("/{id}/edit", [PeringatanKaryawanController::class, "edit"])->middleware("permission:peringatan.edit");
    Route::put("/{id}", [PeringatanKaryawanController::class, "update"])->middleware("permission:peringatan.edit");
    Route::delete("/{id}", [PeringatanKaryawanController::class, "destroy"])->middleware("permission:peringatan.delete");
    Route::get("/{id}/ubah-status", [PeringatanKaryawanController::class, "ubah_status"])->middleware("permission:peringatan.change_status");
    Route::put("/{id}/ubah-status", [PeringatanKaryawanController::class, "update_status"])->middleware("permission:peringatan.change_status");
});
