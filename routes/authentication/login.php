<?php

use App\Http\Controllers\Authentication\LoginController;
use Illuminate\Support\Facades\Route;

Route::prefix("login")->group(function() {
    Route::get("/", [LoginController::class, "login"]);
    Route::post("/", [LoginController::class, "post_login"]);
});
