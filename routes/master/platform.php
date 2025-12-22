<?php

use App\Http\Controllers\Master\PlatformController;
use Illuminate\Support\Facades\Route;

Route::prefix("platform")->group(function() {
    Route::get("/", [PlatformController::class, "index"]);
    Route::post("/", [PlatformController::class, "store"]);
});
