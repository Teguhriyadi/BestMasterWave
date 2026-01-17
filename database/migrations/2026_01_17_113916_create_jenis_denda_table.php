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
        Schema::create('jenis_denda', function (Blueprint $table) {
            $table->uuid("id", 50)->primary();
            $table->string("kode", 50)->unique()->index();
            $table->string("nama_jenis", 100)->index();
            $table->unsignedBigInteger("nominal")->default(0);
            $table->text("keterangan")->nullable();
            $table->enum("is_active", ["1", "0"]);
            $table->uuid('created_by');
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->uuid('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_denda');
    }
};
