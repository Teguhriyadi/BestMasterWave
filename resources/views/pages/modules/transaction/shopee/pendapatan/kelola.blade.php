@extends('pages.layouts.app')

@push('title_module', 'List Data Shopee Pendapatan')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Pendapatan Shopee
    </h1>

    <a href="{{ url('/admin-panel/shopee/pendapatan') }}" class="btn btn-danger btn-sm mb-4">
        <i class="fa fa-sign-out-alt"></i> Kembali
    </a>

    @if (session('success'))
        <div class="alert alert-success">
            <strong>Berhasil,</strong> {{ session('success') }}
        </div>
    @elseif(session("error"))
        <div class="alert alert-danger">
            <strong>Gagal,</strong> {{ session('error') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List Data Pendapatan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>No. Pesanan</th>
                            <th>Nama Seller</th>
                            <th>Username</th>
                            <th class="text-center">Waktu Pesanan</th>
                            <th class="text-center">Tanggal Dana Dilepaskan</th>
                            <th class="text-center">Harga Asli</th>
                            <th class="text-center">Metode Pembayaran</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $nomer = 0
                        @endphp
                        @foreach ($kelola as $item)
                            <tr>
                                <td class="text-center">{{ ++$nomer }}.</td>
                                <td>{{ $item->no_pesanan }}</td>
                                <td>{{ $item->nama_seller }}</td>
                                <td>{{ $item->username }}</td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($item->waktu_pesanan)->translatedFormat('d F Y') }}
                                </td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($item->tanggal_dana_dilepaskan)->translatedFormat('d F Y') }}
                                </td>
                                <td class="text-center">
                                    {{ number_format($item->harga_asli, 0, ',', '.') }}
                                </td>
                                <td class="text-center">{{ $item->metode_pembayaran }}</td>
                                <td class="text-center">
                                    <a href="{{ url('/admin-panel/shopee/pendapatan/data/' . $item->uuid . '/detail') }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-search"></i> Detail
                                    </a>
                                </td>
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
