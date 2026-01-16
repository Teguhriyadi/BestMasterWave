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
        Schema::create('lokasi', function (Blueprint $table) {
            $table->uuid("id", 50)->primary();
            $table->unsignedBigInteger("kode_lokasi")->default(0)->nullable(); // Ex : 1, 2, 3, 4, 5
            $table->string("nama_lokasi", 100)->index();
            $table->enum("is_active", ["1", "0"])->default("1");
            $table->uuid('divisi_id');
            $table->foreign('divisi_id')->references('id')->on('divisi')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasi');
    }
};
