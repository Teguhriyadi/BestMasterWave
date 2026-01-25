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
        Schema::create('invoice_file_tiktok_pendapatan', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("seller_id");
            $table->foreign('seller_id')->references('id')->on('seller')->cascadeOnDelete();
            $table->string("header_hash")->nullable();
            $table->string("schema_id")->nullable();
            $table->timestamp("uploaded_at")->useCurrent();
            $table->unsignedInteger("total_rows")->default(0);
            $table->timestamp('processed_at')->nullable();
            $table->uuid("divisi_id");
            $table->foreign('divisi_id')->references('id')->on('divisi')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_file_tiktok_pendapatan');
    }
};
