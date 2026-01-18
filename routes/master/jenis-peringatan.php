<?php

use App\Http\Controllers\Master\JenisPeringatanController;
use Illuminate\Support\Facades\Route;

Route::prefix("jenis-peringatan")->group(function() {
    Route::get("/", [JenisPeringatanController::class, "index"])->middleware("permission:jenis-peringatan.read");
    Route::post("/", [JenisPeringatanController::class, "store"])->middleware("permission:jenis-peringatan.create");
    Route::get("/{id}/edit", [JenisPeringatanController::class, "edit"])->middleware("permission:jenis-peringatan.edit");
    Route::put("/{id}", [JenisPeringatanController::class, "update"])->middleware("permission:jenis-peringatan.edit");
    Route::delete("/{id}", [JenisPeringatanController::class, "destroy"])->middleware("permission:jenis-peringatan.delete");
});
