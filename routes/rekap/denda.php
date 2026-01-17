<?php

use App\Http\Controllers\Master\DendaKaryawanController;
use Illuminate\Support\Facades\Route;

Route::prefix("denda")->group(function() {
    Route::get("/", [DendaKaryawanController::class, "index"]);
    Route::get("/create", [DendaKaryawanController::class, "create"]);
    Route::post("/", [DendaKaryawanController::class, "store"]);
    Route::get("/{id}/edit", [DendaKaryawanController::class, "edit"]);
    Route::put("/{id}", [DendaKaryawanController::class, "update"]);
    Route::delete("/{id}", [DendaKaryawanController::class, "destroy"]);
});
