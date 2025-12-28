<?php

use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Route;

Route::get("/dashboard", [AppController::class, "dashboard"]);
Route::get("/logout", [AppController::class, "logout"]);
