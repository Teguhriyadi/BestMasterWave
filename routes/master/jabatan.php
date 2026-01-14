<?php

use App\Http\Controllers\Master\JabatanController;
use Illuminate\Support\Facades\Route;

Route::prefix("jabatan")->group(function() {
    Route::get("/", [JabatanController::class, "index"])->middleware("permission:jabatan.read");
    Route::post("/", [JabatanController::class, "store"])->middleware("permission:jabatan.create");
    Route::get("/{id}/edit", [JabatanController::class, "edit"])->middleware("permission:jabatan.edit");
    Route::put("/{id}", [JabatanController::class, "update"])->middleware("permission:jabatan.edit");
    Route::delete("/{id}", [JabatanController::class, "destroy"])->middleware("permission:jabatan.delete");
});
