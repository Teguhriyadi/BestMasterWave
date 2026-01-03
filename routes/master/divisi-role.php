<?php

use App\Http\Controllers\Master\DivisiRoleController;
use Illuminate\Support\Facades\Route;

Route::prefix("divisi-role")->group(function() {
    Route::get("/", [DivisiRoleController::class, "index"]);
    Route::get("/create", [DivisiRoleController::class, "create"]);
    Route::post("/", [DivisiRoleController::class, "store"]);
    Route::get("/{divisi}/roles", [DivisiRoleController::class, "getRoles"]);
    Route::get("/{id}/edit", [DivisiRoleController::class, "edit"]);
    Route::put("/{id}", [DivisiRoleController::class, "update"]);
    Route::delete("/{id}", [DivisiRoleController::class, "destroy"]);
});
