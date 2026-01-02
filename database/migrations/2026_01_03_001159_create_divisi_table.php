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
        Schema::create('divisi', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("nama_divisi", 50)->index();
            $table->enum("is_active", ["1", "0"])->default("1");
            $table->uuid('created_by')->nullable()->index();
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            $table->uuid('updated_by')->nullable()->index();
            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('divisi');
    }
};
