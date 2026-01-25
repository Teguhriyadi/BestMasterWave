@extends('pages.layouts.app')

@push('title_module', 'Detail Data Shopee Pesanan')

@push('css_style')
    <style>
        .judul {
            width: 20%;
        }

        .pemisah {
            width: 10px;
            text-align: center;
        }
    </style>
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Detail Data Pesanan Shopee
    </h1>

    <a href="{{ url('/admin-panel/shopee-pesanan/data') }}" class="btn btn-danger btn-sm mb-4">
        <i class="fa fa-sign-out-alt"></i> Kembali
    </a>

    @if (session('success'))
        <div class="alert alert-success">
            <strong>Berhasil,</strong> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Data Pesanan</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td class="judul">No. Pesanan</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->no_pesanan }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Status Pesanan</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->status_pesanan }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Alasan Pembatalan</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->alasan_pembatalan }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Status Pembatalan</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->status_pembatalan }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">No. Resi</td>
                        <td class="pemisah">:</td>
                        <td>{{ $detail->no_resi }}</td>
                    </tr>
                    <tr>
                        <td class="judul">Opsi Pengiriman</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->opsi_pengiriman }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Anter Ke Counter</td>
                        <td class="pemisah">:</td>
                        <td>{{ $detail->anter_ke_counter }}</td>
                    </tr>
                    <tr>
                        <td class="judul">Pesanan Harus Dikirimkan</td>
                        <td class="pemisah">:</td>
                        <td>
                           {{ \Carbon\Carbon::parse($detail->pesanan_harus_dikirimkan)->translatedFormat('d F Y H:i:s') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Waktu Pengiriman Diatur</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ \Carbon\Carbon::parse($detail->waktu_pengiriman_diatur)->translatedFormat('d F Y H:i:s') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Waktu Pesanan Dibuat</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ \Carbon\Carbon::parse($detail->waktu_pesanan_dibuat)->translatedFormat('d F Y H:i:s') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Waktu Pembayaran Dilakukan</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ \Carbon\Carbon::parse($detail->waktu_pembayaran_dilakukan)->translatedFormat('d F Y H:i:s') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Metode Pembayaran</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->metode_pembayaran }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">SKU Induk</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->sku_induk }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Nama Produk</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->nama_produk }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Nomor Referensi SKU</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->nomor_referensi_sku }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Nama Variasi</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->nama_variasi }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Harga Awal</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->harga_awal, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Harga Setelah Diskon</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->harga_setelah_diskon, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Jumlah</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->jumlah }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Return QTY</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->return_qty }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Total Harga Produk</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->total_harga_produk, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Total Diskon</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->total_diskon, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Diskon Dari Penjual</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->diskon_dari_penjual, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Diskon Dari Shopee</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->diskon_dari_shopee, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Berat Produk</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->berat_produk }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Jumlah Produk di Pesan</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->jumlah_produk_di_pesan }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Total Berat</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->total_berat }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Voucher Ditanggung Penjual</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->voucher_ditanggung_penjual }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Cashback Koin</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->cashback_koin, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Voucher Ditanggung Shopee</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->voucher_ditanggung_shopee, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Paket Diskon</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->paket_diskon == "N" ? "Tidak" : "Ya" }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Kompensasi</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->kompensasi, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Paket Diskon Dari Shopee</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->paket_diskon_dari_shopee, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Paket Diskon Dari Penjual</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->paket_diskon_dari_penjual, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Potongan Koin Shopee</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->potongan_koin_shopee, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Diskon Kartu Kredit</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->diskon_kartu_kredit, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Ongkos Kirim Dibayar Pembeli</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->ongkos_kirim_dibayar_pembeli, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Estimasi Potongan Pengiriman</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->estimasi_potongan_pengiriman, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Ongkos Kirim Pengembalian</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->ongkos_kirim_pengembalian, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Total Pembayaran</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->total_pembayaran, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Perkiraan Ongkos Kirim</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->perkiraan_ongkos_kirim, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Catatan Pembeli</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->catatan_pembeli }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Catatan</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->catatan }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Username</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->username }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Nama Penerima</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->nama_penerima }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">No. Telepon</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->no_telepon }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Alamat</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->alamat_pengiriman }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Kota / Kabupaten</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->kota_kabupaten }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Provinsi</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->provinsi }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Waktu Pesanan Selesai</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ \Carbon\Carbon::parse($detail->waktu_pesanan_selesai)->translatedFormat('d F Y H:i:s') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endpush

@push('js_style')
    <script src="{{ asset('templating/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('templating/js/demo/datatables-demo.js') }}"></script>
@endpush
