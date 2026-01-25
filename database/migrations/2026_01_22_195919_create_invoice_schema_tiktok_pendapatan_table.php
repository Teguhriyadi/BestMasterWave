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
        Schema::create('invoice_schema_tiktok_pendapatan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('header_hash', 64)->unique();
            $table->json('columns_mapping');
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
        Schema::dropIfExists('invoice_schema_tiktok_pendapatan');
    }
};
