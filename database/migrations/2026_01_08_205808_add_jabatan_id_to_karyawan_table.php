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
        Schema::table('karyawan', function (Blueprint $table) {
            $table->uuid("jabatan_id")->nullable()->after("created_by");
            $table->foreign('jabatan_id')->references('id')->on('jabatan')->cascadeOnDelete();

            $table->uuid("divisi_id")->nullable()->after("jabatan_id");
            $table->foreign('divisi_id')->references('id')->on('jabatan')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            //
        });
    }
};
