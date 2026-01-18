<?php

use App\Http\Controllers\Pengaturan\ProfilSayaController;
use Illuminate\Support\Facades\Route;

Route::prefix("profil-saya")->group(function() {
    Route::get("/", [ProfilSayaController::class, "index"]);
    Route::put("/{id}", [ProfilSayaController::class, "update"]);
    Route::put("/{id}/ubah-password", [ProfilSayaController::class, "ubah_password"]);
});
