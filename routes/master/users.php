<?php

use App\Http\Controllers\Master\UserDivisiRoleController;
use Illuminate\Support\Facades\Route;

Route::prefix("users")->group(function() {
    Route::get("/", [UserDivisiRoleController::class, "index"])->middleware("permission:users.read");
    Route::get("/create", [UserDivisiRoleController::class, "create"])->middleware("permission:users.create");
    Route::post("/", [UserDivisiRoleController::class, "store"])->middleware("permission:users.create");
    Route::get("/{id}/edit", [UserDivisiRoleController::class, "edit"])->middleware("permission:users.edit");
    Route::put("/{id}", [UserDivisiRoleController::class, "update"])->middleware("permission:users.edit");
    Route::get("/{id}/detail", [UserDivisiRoleController::class, "detail"])->middleware("permission:users.show");
    Route::put("/{id}/change-status", [UserDivisiRoleController::class, "change_status"])->middleware("permission:users.change_status");
    Route::delete("/{id}", [UserDivisiRoleController::class, "destroy"])->middleware("permission:users.delete");
});
