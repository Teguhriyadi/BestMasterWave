<?php

use App\Http\Controllers\Keuangan\KasbonController;
use Illuminate\Support\Facades\Route;

Route::prefix("kasbon")->group(function() {
    Route::get("/", [KasbonController::class, "index"]);
    Route::post("/", [KasbonController::class, "store"]);
    Route::get("/{id}/edit", [KasbonController::class, "edit"]);
    Route::put("/{id}", [KasbonController::class, "update"]);
    Route::get("/{id}/show", [KasbonController::class, "show"]);
    Route::post("/{kasbon}/topup", [KasbonController::class, "topup"]);
    Route::post("/{kasbon}/bayar", [KasbonController::class, "bayar"]);
    Route::delete("/{id}", [KasbonController::class, "destroy"]);
});
