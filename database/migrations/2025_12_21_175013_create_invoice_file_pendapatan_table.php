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
        Schema::create('invoice_file_pendapatan', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid('seller_id');
            $table->foreign('seller_id', 'fk_invoice_file_pendapatan_platform')
                ->references('id')
                ->on('seller')
                ->cascadeOnDelete();
            $table->timestamp("uploaded_at")->useCurrent();
            $table->date("from_date");
            $table->date("to_date");
            $table->unsignedInteger("total_rows")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_file_pendapatan');
    }
};
