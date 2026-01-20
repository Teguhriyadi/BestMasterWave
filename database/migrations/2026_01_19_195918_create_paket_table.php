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
        Schema::create('paket', function (Blueprint $table) {
            $table->uuid("id", 50)->primary();
            $table->string("sku_paket", 100)->unique();
            $table->string("nama_paket", 150)->index();
            $table->unsignedBigInteger("harga_jual");
            $table->enum("status", ["A", "N"])->default("A");
            $table->uuid('seller_id')->nullable();
            $table->foreign('seller_id')->references('id')->on('seller')->cascadeOnDelete();
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
        Schema::dropIfExists('paket');
    }
};
