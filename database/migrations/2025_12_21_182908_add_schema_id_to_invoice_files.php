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
        Schema::table('invoice_file_pendapatan', function (Blueprint $table) {
            $table->uuid('schema_id')->after('seller_id')->nullable();

            $table->foreign('schema_id')
                ->references('id')
                ->on('invoice_schema_pendapatan')
                ->nullOnDelete();
            $table->string('header_hash', 64)->nullable()->after('schema_id');
        });

        Schema::table('invoice_file_pesanan', function (Blueprint $table) {
            $table->uuid('schema_id')->after('seller_id')->nullable();

            $table->foreign('schema_id')
                ->references('id')
                ->on('invoice_schema_pesanan')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_file_pendapatan', function (Blueprint $table) {
            $table->dropForeign(['schema_id']);
            $table->dropColumn('schema_id');
        });

        Schema::table('invoice_file_pesanan', function (Blueprint $table) {
            $table->dropForeign(['schema_id']);
            $table->dropColumn('schema_id');
        });
    }
};
