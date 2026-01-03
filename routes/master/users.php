<?php

use App\Http\Controllers\Master\UserDivisiRoleController;
use Illuminate\Support\Facades\Route;

Route::prefix("users")->group(function() {
    Route::get("/", [UserDivisiRoleController::class, "index"]);
    Route::get("/create", [UserDivisiRoleController::class, "create"]);
    Route::post("/", [UserDivisiRoleController::class, "store"]);
    Route::get("/{id}/edit", [UserDivisiRoleController::class, "edit"]);
    Route::put("/{id}", [UserDivisiRoleController::class, "update"]);
    Route::delete("/{id}", [UserDivisiRoleController::class, "destroy"]);
});
