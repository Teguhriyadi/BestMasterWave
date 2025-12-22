<?php

use App\Http\Controllers\Transaction\PendapatanController;
use Illuminate\Support\Facades\Route;

Route::prefix("pendapatan")->group(function() {
    Route::get("/", [PendapatanController::class, "index"]);
    Route::post("/", [PendapatanController::class, "store"]);
    Route::post("/process", [PendapatanController::class, "process"]);
    Route::get("/{id}/show", [PendapatanController::class, "show"]);
    Route::get("/preview/{upload}", [PendapatanController::class, "previewData"]);
});
