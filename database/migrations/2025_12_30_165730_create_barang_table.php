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
        Schema::create('barang', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string('sku_barang', 50)->index();
            $table->unsignedBigInteger('harga_modal');
            $table->unsignedBigInteger('harga_pembelian_terakhir');
            $table->dateTime('tanggal_pembelian_terakhir');
            $table->uuid('created_by')->nullable()->index();
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            $table->uuid('updated_by')->nullable()->index();
            $table->foreign('updated_by')
            ->references('id')
            ->on('users')
            ->nullOnDelete();

            $table->string('status_sku', 50)->index()->nullable();

            $table->uuid('seller_id')->nullable()->index();
            $table->foreign('seller_id')
                ->references('id')
                ->on('seller')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
