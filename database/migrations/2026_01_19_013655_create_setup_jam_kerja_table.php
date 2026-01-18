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
        Schema::create('setup_jam_kerja', function (Blueprint $table) {
            $table->uuid("id", 50)->primary();
            $table->time("jam_masuk");
            $table->time("jam_pulang");
            $table->integer('toleransi_menit')->default(0);
            $table->uuid('divisi_id');
            $table->foreign('divisi_id')->references('id')->on('divisi')->cascadeOnDelete();
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
        Schema::dropIfExists('setup_jam_kerja');
    }
};
