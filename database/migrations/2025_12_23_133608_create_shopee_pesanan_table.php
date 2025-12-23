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
            $table->string("no_pesanan", 50)->index();
            $table->string("status_pesanan", 30)->index();
            $table->text("alasan_pembatalan")->nullable();
            $table->string("status_pembatalan", 30)->nullable()->index();
            $table->string("no_resi", 30)->nullable()->index();
            $table->string("opsi_pengiriman", 50)->index();
            $table->string("anter_ke_counter", 50)->index();
            $table->dateTime("pesanan_harus_dikirimkan");
            $table->dateTime("waktu_pengiriman_diatur")->nullable();
            $table->dateTime("waktu_pesanan_dibuat");
            $table->dateTime("waktu_pembayaran_dilakukan");
            $table->string("metode_pembayaran", 50)->index();
            $table->string("sku_induk", 30)->nullable()->index();
            $table->string("nama_produk", 150)->index();
            $table->string("nomor_referensi_sku", 50)->index();
            $table->string("nama_variasi", 50)->nullable()->index();
            $table->bigInteger("harga_awal")->default(0);
            $table->bigInteger("harga_setelah_diskon")->default(0);
            $table->bigInteger("jumlah")->default(0);
            $table->bigInteger("return_qty")->default(0);
            $table->bigInteger("total_harga_produk")->default(0);
            $table->bigInteger("total_diskon")->default(0);
            $table->bigInteger("diskon_dari_penjual")->default(0);
            $table->bigInteger("diskon_dari_shopee")->default(0);
            $table->string("berat_produk", 30)->nullable();
            $table->bigInteger("jumlah_produk_di_pesan")->default(0);
            $table->string("total_berat", 30)->nullable();
            $table->bigInteger("voucher_ditanggung_penjual");
            $table->bigInteger("cashback_koin");
            $table->bigInteger("voucher_ditanggung_shopee");
            $table->string("paket_diskon", 10)->nullable();
            $table->bigInteger("paket_diskon_dari_shopee")->default(0);
            $table->bigInteger("paket_diskon_dari_penjual")->default(0);
            $table->bigInteger("potongan_koin_shopee")->default(0);
            $table->bigInteger("diskon_kartu_kredit")->default(0);
            $table->bigInteger("ongkos_kirim_dibayar_pembeli")->default(0);
            $table->bigInteger("estimasi_potongan_pengiriman")->default(0);
            $table->bigInteger("ongkos_kirim_pengembalian")->default(0);
            $table->bigInteger("total_pembayaran")->default(0);
            $table->bigInteger("perkiraan_ongkos_kirim")->default(0);
            $table->text("catatan_pembeli")->nullable();
            $table->string("username", 100)->index();
            $table->string("nama_penerima", 150)->index();
            $table->string("no_telepon", 30)->index();
            $table->text("alamat")->nullable();
            $table->string("kota_kabupaten", 100)->nullable();
            $table->string("provinsi", 50)->nullable();
            $table->dateTime("waktu_pesanan_selesai")->nullable();
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
