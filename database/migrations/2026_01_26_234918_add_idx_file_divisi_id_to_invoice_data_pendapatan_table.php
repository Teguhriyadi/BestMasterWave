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
        Schema::table('invoice_data_pendapatan', function (Blueprint $table) {
            $table->index(
                ['invoice_file_pendapatan_id', 'divisi_id', 'id'],
                'idx_file_divisi_id'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_data_pendapatan', function (Blueprint $table) {
            $table->dropIndex('idx_file_divisi_id');
        });
    }
};
