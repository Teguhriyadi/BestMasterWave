<?php

use App\Http\Controllers\Master\SupplierController;
use Illuminate\Support\Facades\Route;

Route::prefix("supplier")->group(function() {
    Route::get("/", [SupplierController::class, "index"]);
    Route::post("/", [SupplierController::class, "store"]);
    Route::get("/{id}/edit", [SupplierController::class, "edit"]);
    Route::put("/{id}", [SupplierController::class, "update"]);
    Route::delete("/{id}", [SupplierController::class, "destroy"]);
});
