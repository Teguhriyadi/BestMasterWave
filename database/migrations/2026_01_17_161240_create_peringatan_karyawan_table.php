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
        Schema::create('peringatan_karyawan', function (Blueprint $table) {
            $table->uuid("id", 50)->primary();
            $table->uuid('karyawan_id');
            $table->uuid('jenis_peringatan_id');
            $table->date("tanggal_pelanggaran");
            $table->date("tanggal_terbit_sp")->nullable();
            $table->date("berlaku_sampai")->nullable();
            $table->text("keterangan")->nullable();
            $table->enum("status", ["Draft", "Aktif", "Expired", "Dicabut"]);
            $table->uuid('created_by');
            $table->uuid('approved_by')->nullable();
            $table->dateTime("approved_at")->nullable();

            $table->foreign('jenis_peringatan_id')->references('id')->on('jenis_peringatan')->cascadeOnDelete();
            $table->foreign('karyawan_id')->references('id')->on('karyawan')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('approved_by')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peringatan_karyawan');
    }
};
