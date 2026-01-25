<?php

use App\Http\Controllers\Transaction\Tiktok\PendapatanController;
use Illuminate\Support\Facades\Route;

Route::prefix("tiktok-pendapatan")->group(function() {
    Route::get("/", [PendapatanController::class, "index"])->middleware("permission:tiktok-pendapatan.read");
    Route::post("/", [PendapatanController::class, "store"])->middleware("permission:tiktok-pendapatan.create");
    Route::post("/process", [PendapatanController::class, "process"])->middleware("permission:tiktok-pendapatan.create");
    Route::get("/{id}/show", [PendapatanController::class, "show"]);
    Route::post("/{id}/process-database", [PendapatanController::class, "processDatabase"])->middleware("permission:tiktok-pendapatan.edit");
    Route::prefix("data")->group(function() {
        Route::get("/", [PendapatanController::class, "kelola"])->middleware("permission:tiktok-pendapatan.read");
        Route::get("/{uuid}/detail", [PendapatanController::class, "detail"])->middleware("permission:tiktok-pendapatan.show");
    });
});
