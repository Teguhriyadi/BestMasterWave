<?php

use App\Http\Controllers\Master\SellerController;
use Illuminate\Support\Facades\Route;

Route::prefix("seller")->group(function() {
    Route::get("/", [SellerController::class, "index"]);
    Route::post("/", [SellerController::class, "store"]);
});
