<?php

use App\Http\Controllers\Master\SupplierController;
use Illuminate\Support\Facades\Route;

Route::prefix("supplier")->group(function() {
    Route::get("/", [SupplierController::class, "index"])->middleware("permission:supplier.read");
    Route::post("/", [SupplierController::class, "store"])->middleware("permission:supplier.create");
    Route::get("/{id}/edit", [SupplierController::class, "edit"])->middleware("permission:supplier.edit");
    Route::put("/{id}", [SupplierController::class, "update"])->middleware("permission:supplier.edit");
    Route::delete("/{id}", [SupplierController::class, "destroy"])->middleware("permission:supplier.delete");
});
