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
        Schema::create('supplier', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string("nama_supplier", 100)->index();
            $table->text("alamat")->nullable();
            $table->string("kontak_hubungi", 30)->nullable(); // Email // Whatsapp
            $table->string("nomor_kontak", 30)->nullable();
            $table->integer("ketentuan_tempo_pembayaran")->default(0);
            $table->string("no_rekening", 30)->nullable();
            $table->uuid("bank_id")->nullable()->index();
            $table->string("nama_rekening", 100)->nullable();
            $table->string("pkp", 100)->nullable();
            $table->string("no_npwp", 30)->nullable();
            $table->integer("rate_ppn")->default(0);
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

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier');
    }
};
