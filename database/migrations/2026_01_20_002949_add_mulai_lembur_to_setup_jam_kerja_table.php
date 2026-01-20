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
        Schema::table('setup_jam_kerja', function (Blueprint $table) {
            $table->integer("mulai_lembur")->default(0)->after("toleransi_menit");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setup_jam_kerja', function (Blueprint $table) {
            //
        });
    }
};
