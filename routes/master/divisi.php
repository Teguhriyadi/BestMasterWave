<?php

use App\Http\Controllers\Master\DivisiController;
use Illuminate\Support\Facades\Route;

Route::prefix("divisi")->group(function() {
    Route::get("/", [DivisiController::class, "index"])->middleware("permission:divisi.read");
    Route::post("/", [DivisiController::class, "store"])->middleware("permission:divisi.create");
    Route::get("/{id}/edit", [DivisiController::class, "edit"])->middleware("permission:divisi.edit");
    Route::get("/{id}/roles", [DivisiController::class, "getRoleDivisi"]);
    Route::put("/{id}", [DivisiController::class, "update"])->middleware("permission:divisi.edit");
    Route::delete("/{id}", [DivisiController::class, "destroy"])->middleware("permission:divisi.delete");
});
