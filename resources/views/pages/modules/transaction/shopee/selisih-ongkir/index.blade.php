@extends('pages.layouts.app')

@push('title_module', 'Selisih Ongkir')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Selisih Ongkir
    </h1>

    @if (session('success'))
        <div class="alert alert-success">
            <strong>Berhasil,</strong> {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            <strong>Gagal</strong>,{{ session('error') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>No. Pesanan</th>
                            <th>SKU</th>
                            <th>QTY</th>
                            <th>Nama Seller</th>
                            <th class="text-center">Ongkir Dibayar Pembeli</th>
                            <th class="text-center">Diskon Ongkir Ditanggung Jasa Kirim</th>
                            <th class="text-center">Gratis Ongkir dari Shopee</th>
                            <th class="text-center">Ongkir yang Diteruskan oleh Shopee ke Jasa Kirim</th>
                            <th class="text-center">Ongkos Kirim Pengembalian Barang</th>
                            <th class="text-center">Kembali ke Biaya Pengiriman Pengirim</th>
                            <th class="text-center">Pengembalian Biaya Kirim</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $nomer = 1;
                        @endphp
                        @foreach ($shopee_pendapatan as $item)
                            <tr>
                                <td class="text-center">{{ $nomer++ }}.</td>
                                <td>{{ $item->nomor_referensi_sku }}</td>
                                <td>{{ $item->nomor_referensi_sku }}</td>
                                <td>{{ $item->return_qty }}</td>
                                <td>{{ $item->nama_seller }}</td>
                                <td class="text-center">{{ $item->ongkir_dibayar }}</td>
                                <td class="text-center">{{ $item->diskon_ongkir_ditanggung }}</td>
                                <td class="text-center">{{ $item->gratis_ongkir_shopee }}</td>
                                <td class="text-center">{{ $item->ongkir_diteruskan_shopee }}</td>
                                <td class="text-center">{{ $item->ongkos_kirim_pengembalian }}</td>
                                <td class="text-center">{{ $item->kembali_ke_biaya_pengiriman }}</td>
                                <td class="text-center">{{ $item->pengembalian_biaya_kirim }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endpush

@push('js_style')
    <script src="{{ asset('templating/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('templating/js/demo/datatables-demo.js') }}"></script>
@endpush
