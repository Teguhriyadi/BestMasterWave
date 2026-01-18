<?php

use App\Http\Controllers\Master\SetupJamKerjaController;
use Illuminate\Support\Facades\Route;

Route::prefix("setup-jam-kerja")->group(function() {
    Route::get("/", [SetupJamKerjaController::class, "index"])->middleware("permission:setup-jam-kerja.read");
    Route::post("/", [SetupJamKerjaController::class, "store"])->middleware("permission:setup-jam-kerja.create");
    Route::get("/{id}/edit", [SetupJamKerjaController::class, "edit"])->middleware("permission:setup-jam-kerja.edit");
    Route::put("/{id}", [SetupJamKerjaController::class, "update"])->middleware("permission:setup-jam-kerja.edit");
    Route::delete("/{id}", [SetupJamKerjaController::class, "destroy"])->middleware("permission:setup-jam-kerja.delete");
});
