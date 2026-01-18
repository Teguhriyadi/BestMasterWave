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
        Schema::create('kasbon_transaksi', function (Blueprint $table) {
            $table->uuid("id", 50)->primary();
            $table->enum("tipe", ["topup", "pembayaran"]);
            $table->decimal("nominal", 15, 2);
            $table->date("tanggal");
            $table->enum("metode", ["potong_gaji", "cash", "transfer"])->nullable();
            $table->text("keterangan")->nullable();
            $table->uuid('created_by');
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->uuid('kasbon_id');
            $table->foreign('kasbon_id')->references('id')->on('kasbon')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kasbon_transaksi');
    }
};
