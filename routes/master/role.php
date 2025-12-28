<?php

use App\Http\Controllers\Master\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix("role")->group(function() {
    Route::get("/", [RoleController::class, "index"]);
    Route::post("/", [RoleController::class, "store"]);
});
