<?php

use App\Http\Controllers\Master\PaketController;
use Illuminate\Support\Facades\Route;

Route::prefix("paket")->group(function() {
    Route::get("/", [PaketController::class, "index"]);
    Route::get("/create", [PaketController::class, "create"]);
    Route::post("/", [PaketController::class, "store"]);
    Route::get("/{id}/edit", [PaketController::class, "edit"]);
    Route::put("/{id}", [PaketController::class, "update"]);
    Route::delete("/{id}", [PaketController::class, "destroy"]);
});
