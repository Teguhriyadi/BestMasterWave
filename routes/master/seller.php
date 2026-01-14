<?php

use App\Http\Controllers\Master\SellerController;
use Illuminate\Support\Facades\Route;

Route::prefix("seller")->group(function() {
    Route::get("/", [SellerController::class, "index"])->middleware("permission:seller.read");
    Route::post("/", [SellerController::class, "store"])->middleware("permission:seller.create");
    Route::get("/{id}/edit", [SellerController::class, "edit"])->middleware("permission:seller.edit");
    Route::put("/{id}", [SellerController::class, "update"])->middleware("permission:seller.edit");
    Route::delete("/{id}", [SellerController::class, "destroy"])->middleware("permission:seller.delete");
});
