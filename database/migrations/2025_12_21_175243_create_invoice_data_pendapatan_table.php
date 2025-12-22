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
        Schema::create('invoice_data_pendapatan', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->uuid('invoice_file_pendapatan_id');
            $table->foreign('invoice_file_pendapatan_id', 'fk_invoice_data_pendapatan_platform')
                ->references('id')
                ->on('invoice_file_pendapatan')
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
        Schema::dropIfExists('invoice_data_pendapatan');
    }
};
