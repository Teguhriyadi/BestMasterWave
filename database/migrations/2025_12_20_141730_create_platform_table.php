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
        Schema::create('platform', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("nama", 50)->index();
            $table->string("slug", 50)->index();
            $table->enum("status", ["1", "0"])->default("1");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform');
    }
};
