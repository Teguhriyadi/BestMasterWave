<?php

use App\Http\Controllers\Master\JabatanController;
use Illuminate\Support\Facades\Route;

Route::prefix("jabatan")->group(function() {
    Route::get("/", [JabatanController::class, "index"]);
    Route::post("/", [JabatanController::class, "store"]);
    Route::get("/{id}/edit", [JabatanController::class, "edit"]);
    Route::put("/{id}", [JabatanController::class, "update"]);
    Route::delete("/{id}", [JabatanController::class, "destroy"]);
});
