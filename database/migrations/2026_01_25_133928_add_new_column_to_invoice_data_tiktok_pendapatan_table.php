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
        Schema::table('invoice_data_tiktok_pendapatan', function (Blueprint $table) {
            $table->uuid('invoice_file_tiktok_pendapatan_id')->after("id");

            $table->foreign(
                'invoice_file_tiktok_pendapatan_id',
                'fk_invoice_file_tiktok'
            )->references('id')
                ->on('invoice_file_tiktok_pendapatan')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_data_tiktok_pendapatan', function (Blueprint $table) {
            //
        });
    }
};
