<?php

use App\Http\Controllers\Master\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix("role")->group(function() {
    Route::get("/", [RoleController::class, "index"])->middleware("permission:role.read");
    Route::post("/", [RoleController::class, "store"])->middleware("permission:role.create");
    Route::get("/{id}/edit", [RoleController::class, "edit"])->middleware("permission:role.edit");
    Route::put("/{id}", [RoleController::class, "update"])->middleware("permission:role.edit");
    Route::delete("/{id}", [RoleController::class, "destroy"])->middleware("permission:role.delete");
});
