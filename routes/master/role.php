<?php

use App\Http\Controllers\Master\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix("role")->group(function() {
    Route::get("/", [RoleController::class, "index"]);
    Route::post("/", [RoleController::class, "store"]);
    Route::get("/{id}/edit", [RoleController::class, "edit"]);
    Route::put("/{id}", [RoleController::class, "update"]);
    Route::delete("/{id}", [RoleController::class, "destroy"]);
});
