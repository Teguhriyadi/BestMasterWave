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
        Schema::create('invoice_data_tiktok_pendapatan', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->unsignedInteger('chunk_index')->index();
            $table->json('payload');
            $table->uuid("divisi_id");
            $table->foreign('divisi_id')->references('id')->on('divisi')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_data_tiktok_pendapatan');
    }
};
