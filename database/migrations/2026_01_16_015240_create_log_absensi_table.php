<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_absensi', function (Blueprint $table) {
            $table->uuid("id", 50)->primary();
            $table->uuid('divisi_id');
            $table->unsignedBigInteger('id_fp')->nullable();
            $table->dateTime("tanggal_waktu");
            $table->foreign('divisi_id')->references('id')->on('divisi')->cascadeOnDelete();
            $table->foreign('id_fp')->references('id_fp')->on('karyawan')->cascadeOnDelete();
            $table->unsignedBigInteger("kode_lokasi")->default(0);
            $table->uuid("created_by");
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->uuid("updated_by");
            $table->foreign('updated_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('kode_lokasi')->references('kode_lokasi')->on('lokasi')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_absensi');
    }
};
