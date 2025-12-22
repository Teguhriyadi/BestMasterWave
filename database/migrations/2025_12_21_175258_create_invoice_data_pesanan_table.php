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
        Schema::create('invoice_data_pesanan', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->uuid('invoice_file_pesanan_id');
            $table->foreign('invoice_file_pesanan_id', 'fk_invoice_data_pesanan_platform')
                ->references('id')
                ->on('invoice_file_pesanan')
                ->cascadeOnDelete();
            $table->unsignedInteger('chunk_index')->index();
            $table->json('payload');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_data_pesanan');
    }
};
