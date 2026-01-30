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
        Schema::create('harga_modal_tiktok', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid('sku_barang')->nullable()->index();
            $table->decimal("harga_modal", 20, 2)->default(0);
            $table->decimal("harga_pembelian_terakhir", 20, 2)->default(0);
            $table->date("tanggal_pembelian_terakhir");
            $table->enum('status_sku', ["A", "N"])->index()->default("A");

            $table->uuid('created_by')->nullable()->index();
            $table->uuid('nama_seller')->nullable()->index();
            $table->dateTime("created_at")->nullable();
            $table->uuid('updated_by')->nullable()->index();
            $table->dateTime("updated_at")->nullable();

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            $table->foreign('nama_seller')
                ->references('id')
                ->on('seller')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_modal_tiktok');
    }
};
