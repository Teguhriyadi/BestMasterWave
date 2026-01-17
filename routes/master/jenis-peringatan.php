<?php

use App\Http\Controllers\Master\JenisPeringatanController;
use Illuminate\Support\Facades\Route;

Route::prefix("jenis-peringatan")->group(function() {
    Route::get("/", [JenisPeringatanController::class, "index"]);
    Route::post("/", [JenisPeringatanController::class, "store"]);
    Route::get("/{id}/edit", [JenisPeringatanController::class, "edit"]);
    Route::put("/{id}", [JenisPeringatanController::class, "update"]);
    Route::delete("/{id}", [JenisPeringatanController::class, "destroy"]);
});
