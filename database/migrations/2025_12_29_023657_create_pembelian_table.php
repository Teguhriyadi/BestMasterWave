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
        Schema::create('pembelian', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("sku_barang", 100)->index();
            $table->unsignedBigInteger("qty")->default(0);
            $table->unsignedBigInteger("harga_satuan")->default(0);
            $table->unsignedBigInteger("diskon")->default(0);
            $table->unsignedBigInteger("ppn")->default(0);
            $table->unsignedBigInteger("total")->default(0);
            $table->string("no_invoice", 100)->index();
            $table->dateTime("tanggal_invoice");
            $table->dateTime("tanggal_jatuh_tempo");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};
