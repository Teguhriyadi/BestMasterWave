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
            $table->string("no_invoice", 100)->index();
            $table->date("tanggal_invoice");
            $table->date("tanggal_jatuh_tempo");
            $table->unsignedBigInteger("total_harga")->default(0);
            $table->unsignedBigInteger("total_ppn")->default(0);
            $table->unsignedBigInteger("total_qty")->default(0);
            $table->uuid('supplier_id')->nullable()->index();
            $table->foreign('supplier_id')
                ->references('id')
                ->on('supplier')
                ->nullOnDelete();
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
            $table->text("keterangan")->nullable()->index();
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
