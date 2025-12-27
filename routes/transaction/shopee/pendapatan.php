<?php

use App\Http\Controllers\Transaction\Shopee\PendapatanController;
use Illuminate\Support\Facades\Route;

Route::prefix("shopee/pendapatan")->group(function() {
    Route::get("/", [PendapatanController::class, "index"]);
    Route::post("/", [PendapatanController::class, "store"]);
    Route::post("/process", [PendapatanController::class, "process"]);
    Route::get("/{id}/show", [PendapatanController::class, "show"]);
    Route::get("/preview/{upload}", [PendapatanController::class, "previewData"]);
    Route::post("/{id}/process-database", [PendapatanController::class, "processDatabase"]);
    Route::prefix("data")->group(function() {
        Route::get("/", [PendapatanController::class, "kelola"]);
        Route::get("/{uuid}/detail", [PendapatanController::class, "detail"]);
    });
});
