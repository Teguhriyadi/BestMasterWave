@extends('pages.layouts.app')

@push('title_module', 'Detail Data Shopee Pendapatan')

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
        Detail Data Pendapatan Shopee
    </h1>

    <a href="{{ url('/admin-panel/shopee-pendapatan/data') }}" class="btn btn-danger btn-sm mb-4">
        <i class="fa fa-sign-out-alt"></i> Kembali
    </a>

    @if (session('success'))
        <div class="alert alert-success">
            <strong>Berhasil,</strong> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Data Pendapatan</h6>
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
                        <td class="judul">No. Pengajuan</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->no_pengajuan }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Nama Seller</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->nama_seller }}
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
                        <td class="judul">Waktu Pesanan</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ \Carbon\Carbon::parse($detail->waktu_pesanan)->translatedFormat('d F Y') }}
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
                        <td class="judul">Tanggal Dana Dilepaskan</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ \Carbon\Carbon::parse($detail->tanggal_dana_dilepaskan)->translatedFormat('d F Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Harga Asli</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->harga_asli, 0, ',', '.') }}
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
                        <td class="judul">Jumlah Pengembalian</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->jumlah_pengembalian, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Diskon Produk</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->diskon_produk_shopee, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Voucher Penjual</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->voucher_penjual, 0, ',', '.') }}
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
                        <td class="judul">Ongkir Dibayar</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->ongkir_dibayar, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Diskon Ongkir Ditanggung</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->diskon_ongkir_ditanggung, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Gratis Ongkir</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->gratis_ongkir_shopee, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Ongkir Diteruskan Shopee</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->ongkir_diteruskan_shopee, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td>Ongkir Kirim Pengembalian</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->ongkos_kirim_pengembalian, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Kembali Ke Biaya Pengiriman</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->kembali_ke_biaya_pengiriman, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Pengembalian Biaya Kirim</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->pengembalian_biaya_kirim, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Biaya Komisi AMS</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->biaya_komisi_ams, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Biaya Administrasi</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->biaya_administrasi, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Biaya Layanan</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->biaya_layanan, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Biaya Proses Pesanan</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->biaya_proses_pesanan, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Premi</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->premi, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Biaya Program Hemat Biaya</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->biaya_program_hemat_biaya, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Biaya Transaksi</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->biaya_transaksi, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Biaya Kampanye</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->biaya_kampanye, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Bea Masuk PPH</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->bea_masuk_pph, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Total Penghasilan</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->total_penghasilan, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Kode Voucher</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->kode_voucher }}
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
                        <td class="judul">Promo Gratis Ongkir</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->promo_gratis_ongkir, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Jasa Kirim</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ $detail->jasa_kirim }} - {{ $detail->nama_kurir }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Pengembalian Dana Ke Pembeli</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->pengembalian_dana_ke_pembeli, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Pro Rata Koin</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->pro_rata_koin, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Pro Rata Voucher</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->pro_rata_voucher, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Pro Rated Bank</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->pro_rated_bank, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Pro Rated Payment Channel</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->pro_rated_payment_channel, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="judul">Harga Modal</td>
                        <td class="pemisah">:</td>
                        <td>
                            {{ number_format($detail->harga_modal, 0, ',', '.') }}
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
