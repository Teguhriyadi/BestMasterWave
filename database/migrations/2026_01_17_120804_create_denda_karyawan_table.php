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
        Schema::create('denda_karyawan', function (Blueprint $table) {
            $table->uuid("id", 50)->primary();
            $table->uuid('karyawan_id');
            $table->date("tanggal_denda");
            $table->uuid('jenis_denda_id');
            $table->text("keterangan")->nullable();
            $table->date("periode_gaji");
            $table->enum("status", ["Draft", "Disetujui", "Dibatalkan", "Dipotong"])->default("Draft");
            $table->uuid('created_by');
            $table->uuid('updated_by')->nullable();
            $table->uuid('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();

            $table->foreign('karyawan_id')->references('id')->on('karyawan')->cascadeOnDelete();
            $table->foreign('jenis_denda_id')->references('id')->on('jenis_denda')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('approved_by')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('denda_karyawan');
    }
};
