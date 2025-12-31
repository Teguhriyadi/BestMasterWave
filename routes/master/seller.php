<?php

use App\Http\Controllers\Master\SellerController;
use Illuminate\Support\Facades\Route;

Route::prefix("seller")->group(function() {
    Route::get("/", [SellerController::class, "index"]);
    Route::post("/", [SellerController::class, "store"]);
    Route::get("/{id}/edit", [SellerController::class, "edit"]);
    Route::put("/{id}", [SellerController::class, "update"]);
    Route::delete("/{id}", [SellerController::class, "destroy"]);
});
