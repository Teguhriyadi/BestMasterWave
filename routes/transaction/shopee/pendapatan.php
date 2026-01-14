<?php

use App\Http\Controllers\Transaction\Shopee\PendapatanController;
use Illuminate\Support\Facades\Route;

Route::prefix("shopee/pendapatan")->group(function() {
    Route::get("/", [PendapatanController::class, "index"])->middleware("permission:shopee-pendapatan.read");
    Route::post("/", [PendapatanController::class, "store"])->middleware("permission:shopee-pendapatan.create");
    Route::post("/process", [PendapatanController::class, "process"])->middleware("permission:shopee-pendapatan.create");
    Route::get("/{id}/show", [PendapatanController::class, "show"])->middleware("permission:shopee-pendapatan.show");
    Route::get("/preview/{upload}", [PendapatanController::class, "previewData"])->middleware("permission:shopee-pendapatan.show");
    Route::post("/{id}/process-database", [PendapatanController::class, "processDatabase"])->middleware("permission:shopee-pendapatan.edit");
    Route::prefix("data")->group(function() {
        Route::get("/", [PendapatanController::class, "kelola"])->middleware("permission:shopee-pendapatan.read");
        Route::get("/{uuid}/detail", [PendapatanController::class, "detail"])->middleware("permission:shopee-pendapatan.show");
    });
});
