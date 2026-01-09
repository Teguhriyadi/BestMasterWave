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
        Schema::create('log_karyawan', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("karyawan_id");
            $table->foreign('karyawan_id')->references('id')->on('karyawan')->cascadeOnDelete();
            $table->text("deskripsi");
            $table->uuid("created_by");
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_karyawan');
    }
};
