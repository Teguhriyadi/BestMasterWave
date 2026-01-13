<?php

use App\Http\Controllers\Master\KaryawanController;
use Illuminate\Support\Facades\Route;

Route::prefix("karyawan")->group(function() {
    Route::get("/", [KaryawanController::class, "index"])->middleware("permission:karyawan.read");
    Route::get("/create", [KaryawanController::class, "create"])->middleware("permission:karyawan.create");
    Route::post("/", [KaryawanController::class, "store"])->middleware("permission:karyawan.create");
    Route::get("/{id}/show", [KaryawanController::class, "show"]);
    Route::get("/{id}/edit", [KaryawanController::class, "edit"])->middleware("permission:karyawan.edit");;
    Route::get("/{id}/lihat-log", [KaryawanController::class, "lihat_log"]);
    Route::put("/{id}", [KaryawanController::class, "update"])->middleware("permission:karyawan.edit");;
    Route::delete("/{id}", [KaryawanController::class, "destroy"])->middleware("permission:karyawan.delete");;
});
