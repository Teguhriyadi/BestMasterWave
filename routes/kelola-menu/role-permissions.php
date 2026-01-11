<?php

use App\Http\Controllers\PengaturanMenu\RolePermissionsController;
use Illuminate\Support\Facades\Route;

Route::prefix("role-permissions")->group(function() {
    Route::get("/", [RolePermissionsController::class, "index"]);
    Route::get("/create", [RolePermissionsController::class, "create"]);
    Route::post("/", [RolePermissionsController::class, "store"]);
    Route::get("/{id}/edit", [RolePermissionsController::class, "edit"]);
    Route::put("/{id}", [RolePermissionsController::class, "update"]);
    Route::delete("/", [RolePermissionsController::class, "destroy"]);
});
