<?php

use App\Http\Controllers\PengaturanMenu\MenuController;
use Illuminate\Support\Facades\Route;

Route::prefix("role-menu")->group(function() {
    Route::get("/", [MenuController::class, "index"]);
    Route::post("/", [MenuController::class, "store"]);
    Route::get("/{id}/edit", [MenuController::class, "edit"]);
    Route::put("/{id}", [MenuController::class, "update"]);
    Route::delete("/{id}", [MenuController::class, "destroy"]);
});
