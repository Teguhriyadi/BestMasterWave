<?php

use App\Http\Controllers\Keuangan\KasbonController;
use Illuminate\Support\Facades\Route;

Route::prefix("kasbon")->group(function() {
    Route::get("/", [KasbonController::class, "index"])->middleware("permission:kasbon.read");
    Route::post("/", [KasbonController::class, "store"])->middleware("permission:kasbon.create");
    Route::get("/{id}/edit", [KasbonController::class, "edit"])->middleware("permission:kasbon.edit");
    Route::put("/{id}", [KasbonController::class, "update"])->middleware("permission:kasbon.edit");
    Route::get("/{id}/show", [KasbonController::class, "show"])->middleware("permission:kasbon.show");
    Route::post("/{kasbon}/topup", [KasbonController::class, "topup"]);
    Route::post("/{kasbon}/bayar", [KasbonController::class, "bayar"]);
    Route::delete("/{id}", [KasbonController::class, "destroy"])->middleware("permission:kasbon.delete");
});
