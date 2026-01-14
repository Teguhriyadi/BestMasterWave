<?php

use App\Http\Controllers\Master\BankController;
use Illuminate\Support\Facades\Route;

Route::prefix("bank")->group(function() {
    Route::get("/", [BankController::class, "index"])->middleware("permission:bank.read");
    Route::post("/", [BankController::class, "store"])->middleware("permission:bank.create");
    Route::get("/{id}/edit", [BankController::class, "edit"])->middleware("permission:bank.edit");
    Route::put("/{id}", [BankController::class, "update"])->middleware("permission:bank.edit");
    Route::delete("/{id}", [BankController::class, "destroy"])->middleware("permission:bank.delete");
});
