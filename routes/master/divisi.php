<?php

use App\Http\Controllers\Master\DivisiController;
use Illuminate\Support\Facades\Route;

Route::prefix("divisi")->group(function() {
    Route::get("/", [DivisiController::class, "index"]);
    Route::post("/", [DivisiController::class, "store"]);
    Route::get("/{id}/edit", [DivisiController::class, "edit"]);
    Route::get("/{id}/roles", [DivisiController::class, "getRoleDivisi"]);
    Route::put("/{id}", [DivisiController::class, "update"]);
    Route::delete("/{id}", [DivisiController::class, "destroy"]);
});
