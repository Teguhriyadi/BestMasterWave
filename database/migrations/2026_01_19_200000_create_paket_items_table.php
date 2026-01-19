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
        Schema::create('paket_items', function (Blueprint $table) {
            $table->uuid("id", 50)->primary();
            $table->uuid('paket_id');
            $table->uuid('barang_id');
            $table->integer('qty')->default(1);
            $table->unsignedBigInteger("harga_satuan");
            $table->foreign('paket_id')->references('id')->on('paket')->cascadeOnDelete();
            $table->foreign('barang_id')->references('id')->on('barang')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_items');
    }
};
