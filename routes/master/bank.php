<?php

use App\Http\Controllers\Master\BankController;
use Illuminate\Support\Facades\Route;

Route::prefix("bank")->group(function() {
    Route::get("/", [BankController::class, "index"]);
    Route::post("/", [BankController::class, "store"]);
    Route::get("/{id}/edit", [BankController::class, "edit"]);
    Route::put("/{id}", [BankController::class, "update"]);
    Route::delete("/{id}", [BankController::class, "destroy"]);
});
