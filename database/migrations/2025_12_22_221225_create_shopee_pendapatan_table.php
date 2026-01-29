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
        Schema::create('shopee_pendapatan', function (Blueprint $table) {
            $table->id();
            $table->uuid("uuid")->unique();
            $table->string("no_pesanan", 50)->index()->nullable();
            $table->string("no_pengajuan", 50)->nullable()->index()->nullable();
            $table->string("nama_seller")->nullable();
            $table->string("username", 100)->index()->nullable();
            $table->date("waktu_pesanan")->index()->nullable();
            $table->string("metode_pembayaran", 30)->index()->nullable();
            $table->date("tanggal_dana_dilepaskan")->index()->nullable();
            $table->bigInteger("harga_asli")->default(0)->nullable();
            $table->bigInteger("total_diskon")->default(0)->nullable();
            $table->bigInteger("jumlah_pengembalian")->default(0)->nullable();
            $table->bigInteger("diskon_produk_shopee")->default(0)->nullable();
            $table->bigInteger("voucher_penjual")->default(0)->nullable();
            $table->bigInteger("cashback_koin")->default(0)->nullable();
            $table->bigInteger("ongkir_dibayar")->default(0)->nullable();
            $table->bigInteger("diskon_ongkir_ditanggung")->default(0)->nullable();
            $table->bigInteger("gratis_ongkir_shopee")->default(0)->nullable();
            $table->bigInteger("ongkir_diteruskan_shopee")->default(0)->nullable();
            $table->bigInteger("ongkos_kirim_pengembalian")->default(0)->nullable();
            $table->bigInteger("kembali_ke_biaya_pengiriman")->default(0)->nullable();
            $table->bigInteger("pengembalian_biaya_kirim")->default(0)->nullable();
            $table->bigInteger("biaya_komisi_ams")->default(0)->nullable();
            $table->bigInteger("biaya_administrasi")->default(0)->nullable();
            $table->bigInteger("biaya_layanan")->default(0)->nullable();
            $table->bigInteger("biaya_proses_pesanan")->default(0)->nullable();
            $table->bigInteger("premi")->default(0)->nullable();
            $table->bigInteger("biaya_program_hemat_biaya")->default(0)->nullable();
            $table->bigInteger("biaya_transaksi")->default(0)->nullable();
            $table->bigInteger("biaya_kampanye")->default(0)->nullable();
            $table->bigInteger("bea_masuk_pph")->default(0)->nullable();
            $table->bigInteger("total_penghasilan")->default(0)->nullable();
            $table->string("kode_voucher", 100)->nullable()->index();
            $table->bigInteger("kompensasi")->default(0)->nullable();
            $table->bigInteger("promo_gratis_ongkir")->default(0)->nullable();
            $table->string("jasa_kirim", 50)->index()->nullable();
            $table->string("nama_kurir", 50)->index()->nullable();
            $table->bigInteger("pengembalian_dana_ke_pembeli")->default(0)->nullable();
            $table->bigInteger("pro_rata_koin")->default(0)->nullable();
            $table->bigInteger("pro_rata_voucher")->default(0)->nullable();
            $table->bigInteger("pro_rated_bank")->default(0)->nullable();
            $table->bigInteger("pro_rated_payment_channel")->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopee_pendapatan');
    }
};
