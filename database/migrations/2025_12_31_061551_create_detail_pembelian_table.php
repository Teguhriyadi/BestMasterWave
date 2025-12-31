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
        Schema::create('detail_pembelian', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid('pembelian_id')->nullable()->index();
            $table->foreign('pembelian_id')
                ->references('id')
                ->on('pembelian')
                ->nullOnDelete();
            $table->string("sku_barang", 50)->index();
            $table->unsignedBigInteger("qty")->default(0);
            $table->string("satuan", 50)->index();
            $table->unsignedBigInteger("harga_satuan")->default(0);
            $table->unsignedBigInteger("diskon")->default(0);
            $table->unsignedBigInteger("ppn")->default(0);
            $table->unsignedBigInteger("total")->default(0);
            $table->text("keterangan");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pembelian');
    }
};
