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
        Schema::create('role_menu', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid('menu_id')->nullable();
            $table->uuid('role_id')->nullable();
            $table->uuid('divisi_id')->nullable();
            $table->timestamps();

            $table->foreign('menu_id')
                ->references('id')
                ->on('menu')
                ->nullOnDelete();

            $table->foreign('role_id')
                ->references('id')
                ->on('role')
                ->nullOnDelete();

            $table->foreign('divisi_id')
                ->references('id')
                ->on('divisi')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_menu');
    }
};
