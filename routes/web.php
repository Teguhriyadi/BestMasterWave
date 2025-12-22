<?php

use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix("test-app")->group(function() {
    Route::get("/", [AppController::class, "index"]);
    Route::post("/", [AppController::class, "store"]);
    Route::post("/process", [AppController::class, "process"]);
    Route::get("/preview", [AppController::class, "preview"]);
    Route::get("/preview/{upload}", [AppController::class, "previewData"]);
});
