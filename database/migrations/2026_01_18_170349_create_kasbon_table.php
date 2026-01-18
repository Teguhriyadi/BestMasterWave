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
        Schema::create('kasbon', function (Blueprint $table) {
            $table->uuid("id", 50)->primary();
            $table->decimal("jumlah_awal", 15, 2);
            $table->decimal("sisa", 15, 2);
            $table->enum("status", ["aktif", "lunas"])->default("aktif");
            $table->date("tanggal_mulai");
            $table->text("keterangan")->nullable();
            $table->uuid('karyawan_id');
            $table->foreign('karyawan_id')->references('id')->on('karyawan')->cascadeOnDelete();
            $table->uuid('created_by');
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kasbon');
    }
};
