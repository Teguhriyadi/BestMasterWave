<?php

use App\Http\Controllers\Master\PlatformController;
use Illuminate\Support\Facades\Route;

Route::prefix("platform")->group(function() {
    Route::get("/", [PlatformController::class, "index"]);
    Route::post("/", [PlatformController::class, "store"]);
    Route::get("/{id}/edit", [PlatformController::class, "edit"]);
    Route::put("/{id}", [PlatformController::class, "update"]);
    Route::delete("/{id}", [PlatformController::class, "destroy"]);
});
