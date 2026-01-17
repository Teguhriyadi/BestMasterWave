<?php

use App\Http\Controllers\Master\PeringatanKaryawanController;
use Illuminate\Support\Facades\Route;

Route::prefix("peringatan")->group(function() {
    Route::get("/", [PeringatanKaryawanController::class, "index"]);
    Route::get("/create", [PeringatanKaryawanController::class, "create"]);
    Route::post("/", [PeringatanKaryawanController::class, "store"]);
    Route::get("/{id}/edit", [PeringatanKaryawanController::class, "edit"]);
    Route::put("/{id}", [PeringatanKaryawanController::class, "update"]);
    Route::delete("/{id}", [PeringatanKaryawanController::class, "destroy"]);
});
