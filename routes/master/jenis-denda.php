<?php

use App\Http\Controllers\Master\JenisDendaController;
use Illuminate\Support\Facades\Route;

Route::prefix("jenis-denda")->group(function() {
    Route::get("/", [JenisDendaController::class, "index"]);
    Route::post("/", [JenisDendaController::class, "store"]);
    Route::get("/{id}/edit", [JenisDendaController::class, "edit"]);
    Route::put("/{id}", [JenisDendaController::class, "update"]);
    Route::delete("/{id}", [JenisDendaController::class, "destroy"]);
});
