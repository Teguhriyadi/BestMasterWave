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
        Schema::table('invoice_data_tiktok_pesanan', function (Blueprint $table) {
            $table->uuid('invoice_file_tiktok_pesanan_id')->after("id");

            $table->foreign(
                'invoice_file_tiktok_pesanan_id',
                'fk_inv_data_pesanan_file'
            )->references('id')
                ->on('invoice_file_tiktok_pesanan')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_data_tiktok_pesanan', function (Blueprint $table) {
            //
        });
    }
};
