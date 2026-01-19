<?php

use App\Http\Controllers\Master\PaketController;
use Illuminate\Support\Facades\Route;

Route::prefix("paket")->group(function() {
    Route::get("/", [PaketController::class, "index"])->middleware("permission:paket.read");
    Route::get("/create", [PaketController::class, "create"])->middleware("permission:paket.create");
    Route::post("/", [PaketController::class, "store"])->middleware("permission:paket.create");
    Route::get("/{id}/edit", [PaketController::class, "edit"])->middleware("permission:paket.edit");
    Route::put("/{id}", [PaketController::class, "update"])->middleware("permission:paket.edit");
    Route::delete("/{id}", [PaketController::class, "destroy"])->middleware("permission:paket.delete");
});
