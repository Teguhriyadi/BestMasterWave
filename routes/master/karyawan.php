<?php

use App\Http\Controllers\Master\KaryawanController;
use Illuminate\Support\Facades\Route;

Route::prefix("karyawan")->group(function() {
    Route::get("/", [KaryawanController::class, "index"]);
    Route::get("/create", [KaryawanController::class, "create"]);
    Route::post("/", [KaryawanController::class, "store"]);
    Route::get("/{id}/show", [KaryawanController::class, "show"]);
    Route::get("/{id}/edit", [KaryawanController::class, "edit"]);
    Route::get("/{id}/lihat-log", [KaryawanController::class, "lihat_log"]);
    Route::put("/{id}", [KaryawanController::class, "update"]);
    Route::delete("/{id}", [KaryawanController::class, "destroy"]);
});
