<?php

use App\Http\Controllers\PengaturanMenu\PermissionsController;
use Illuminate\Support\Facades\Route;

Route::prefix("permissions")->group(function() {
    Route::get("/", [PermissionsController::class, "index"]);
    Route::post("/", [PermissionsController::class, "store"]);
    Route::get("/{id}/edit", [PermissionsController::class, "edit"]);
    Route::put("/{id}", [PermissionsController::class, "update"]);
    Route::delete("/{id}", [PermissionsController::class, "destroy"]);
});
