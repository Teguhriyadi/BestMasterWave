<?php

use App\Http\Controllers\Master\DendaKaryawanController;
use Illuminate\Support\Facades\Route;

Route::prefix("denda")->group(function() {
    Route::get("/", [DendaKaryawanController::class, "index"])->middleware("permission:denda.read");
    Route::get("/create", [DendaKaryawanController::class, "create"])->middleware("permission:denda.create");
    Route::post("/", [DendaKaryawanController::class, "store"])->middleware("permission:denda.create");
    Route::get("/{id}/edit", [DendaKaryawanController::class, "edit"])->middleware("permission:denda.edit");
    Route::put("/{id}", [DendaKaryawanController::class, "update"])->middleware("permission:denda.edit");
    Route::delete("/{id}", [DendaKaryawanController::class, "destroy"])->middleware("permission:denda.delete");
    Route::get("/{id}/ubah-status", [DendaKaryawanController::class, "ubah_status"])->middleware("permission:denda.change_status");
    Route::put("/{id}/ubah-status", [DendaKaryawanController::class, "update_status"])->middleware("permission:denda.change_status");
});
