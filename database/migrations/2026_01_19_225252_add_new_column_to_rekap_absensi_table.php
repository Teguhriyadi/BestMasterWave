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
        Schema::table('rekap_absensi', function (Blueprint $table) {
            $table->enum("status_approval", ["Diajukan", "Ditolak", "Disetujui"])->default("Diajukan")->after("tanggal");
            $table->uuid('approved_by')->nullable()->after("updated_by");
            $table->dateTime("approved_at")->nullable()->after("approved_by");
            $table->text("reject_notes")->nullable()->after("approved_at");
            $table->foreign('approved_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekap_absensi', function (Blueprint $table) {
            //
        });
    }
};
