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
        Schema::create('shopee_pesanan', function (Blueprint $table) {
            $table->id();
            $table->uuid("uuid")->unique();
            $table->string("no_pesanan", 50)->index()->nullable();
            $table->string("status_pesanan", 30)->index()->nullable();
            $table->text("alasan_pembatalan")->nullable();
            $table->string("status_pembatalan", 30)->nullable()->index();
            $table->string("no_resi", 30)->nullable()->index()->nullable();
            $table->string("opsi_pengiriman", 50)->index()->nullable();
            $table->string("anter_ke_counter", 50)->index()->nullable();
            $table->dateTime("pesanan_harus_dikirimkan")->nullable();
            $table->dateTime("waktu_pengiriman_diatur")->nullable();
            $table->dateTime("waktu_pesanan_dibuat")->nullable();
            $table->dateTime("waktu_pembayaran_dilakukan")->nullable();
            $table->string("metode_pembayaran", 50)->index()->nullable();
            $table->string("sku_induk", 30)->nullable()->index();
            $table->string("nama_produk")->index()->nullable();
            $table->string("nomor_referensi_sku")->index()->nullable();
            $table->string("nama_variasi")->nullable()->index();
            $table->bigInteger("harga_awal")->default(0)->nullable();
            $table->bigInteger("harga_setelah_diskon")->default(0)->nullable();
            $table->bigInteger("jumlah")->default(0)->nullable();
            $table->bigInteger("return_qty")->default(0)->nullable();
            $table->bigInteger("total_harga_produk")->default(0)->nullable();
            $table->bigInteger("total_diskon")->default(0)->nullable();
            $table->bigInteger("diskon_dari_penjual")->default(0)->nullable();
            $table->bigInteger("diskon_dari_shopee")->default(0)->nullable();
            $table->string("berat_produk", 30)->nullable();
            $table->bigInteger("jumlah_produk_di_pesan")->default(0)->nullable();
            $table->string("total_berat", 30)->nullable();
            $table->bigInteger("voucher_ditanggung_penjual")->nullable();
            $table->bigInteger("cashback_koin")->nullable();
            $table->bigInteger("voucher_ditanggung_shopee")->nullable();
            $table->string("paket_diskon", 10)->nullable();
            $table->bigInteger("paket_diskon_dari_shopee")->default(0)->nullable();
            $table->bigInteger("paket_diskon_dari_penjual")->default(0)->nullable();
            $table->bigInteger("potongan_koin_shopee")->default(0)->nullable();
            $table->bigInteger("diskon_kartu_kredit")->default(0)->nullable();
            $table->bigInteger("ongkos_kirim_dibayar_pembeli")->default(0)->nullable();
            $table->bigInteger("estimasi_potongan_pengiriman")->default(0)->nullable();
            $table->bigInteger("ongkos_kirim_pengembalian")->default(0)->nullable();
            $table->bigInteger("total_pembayaran")->default(0)->nullable();
            $table->bigInteger("perkiraan_ongkos_kirim")->default(0)->nullable();
            $table->text("catatan_pembeli")->nullable();
            $table->text("catatan")->nullable();
            $table->string("username", 100)->index()->nullable();
            $table->string("nama_penerima", 150)->index()->nullable();
            $table->string("no_telepon", 30)->index()->nullable();
            $table->text("alamat_pengiriman")->nullable();
            $table->string("kota_kabupaten", 100)->nullable();
            $table->string("provinsi", 50)->nullable();
            $table->dateTime("waktu_pesanan_selesai")->nullable()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopee_pesanan');
    }
};
