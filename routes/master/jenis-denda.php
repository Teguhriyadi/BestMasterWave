<?php

use App\Http\Controllers\Master\JenisDendaController;
use Illuminate\Support\Facades\Route;

Route::prefix("jenis-denda")->group(function() {
    Route::get("/", [JenisDendaController::class, "index"])->middleware("permission:jenis-denda.read");
    Route::post("/", [JenisDendaController::class, "store"])->middleware("permission:jenis-denda.create");
    Route::get("/{id}/edit", [JenisDendaController::class, "edit"])->middleware("permission:jenis-denda.edit");
    Route::put("/{id}", [JenisDendaController::class, "update"])->middleware("permission:jenis-denda.edit");
    Route::delete("/{id}", [JenisDendaController::class, "destroy"])->middleware("permission:jenis-denda.delete");
});
