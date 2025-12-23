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
        Schema::create('seller', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid('platform_id');
            $table->foreign('platform_id', 'fk_seller_platform')
                ->references('id')
                ->on('platform')
                ->cascadeOnDelete();
            $table->string("nama", 100)->index();
            $table->string("slug", 150)->index();
            $table->enum("status", ["1", "0"])->default("1");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller');
    }
};
