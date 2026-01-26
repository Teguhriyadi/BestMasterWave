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
        Schema::table('invoice_data_pesanan', function (Blueprint $table) {
            $table->index(
                ['invoice_file_pesanan_id', 'chunk_index'],
                'idx_file_chunk'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_data_pesanan', function (Blueprint $table) {
            $table->dropIndex('idx_file_chunk');
        });
    }
};
