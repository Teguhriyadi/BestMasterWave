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
        Schema::create('rekap_absensi', function (Blueprint $table) {
            $table->uuid("id", 50)->primary();
            $table->uuid('karyawan_id');
            $table->enum("status", ["Alfa", "Sakit", "Izin", "Cuti"]);
            $table->text("alasan")->nullable();
            $table->string("foto")->nullable();
            $table->date("tanggal");
            $table->foreign('karyawan_id')->references('id')->on('karyawan')->cascadeOnDelete();
            $table->uuid('created_by');
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->uuid('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_absensi');
    }
};
