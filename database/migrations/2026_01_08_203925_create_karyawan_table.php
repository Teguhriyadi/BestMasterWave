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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("id_fp", 50)->unique()->index()->nullable();
            $table->string("no_ktp", 100)->index()->unique()->nullable();
            $table->string("no_kk", 100)->index()->nullable();
            $table->string("no_bpjs_kesehatan", 100)->index()->nullable();
            $table->string("nama", 100)->index();
            $table->string("nama_panggilan", 50)->index();
            $table->date("tanggal_masuk")->nullable();
            $table->date("tanggal_keluar")->nullable();
            $table->string("no_hp", 30)->index()->nullable();
            $table->string("no_hp_darurat", 30)->index()->nullable();
            $table->string("tempat_lahir", 30)->index();
            $table->date("tanggal_lahir")->nullable();
            $table->enum("jenis_kelamin", ["L", "P"]);
            $table->text("alamat");
            $table->string("status_pernikahan", 30);
            $table->uuid("bank_id")->nullable();
            $table->foreign('bank_id')->references('id')->on('bank')->cascadeOnDelete();
            $table->string("acc_no", 50)->index()->nullable();
            $table->string("acc_name", 50)->index()->nullable();
            $table->uuid("created_by");
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
