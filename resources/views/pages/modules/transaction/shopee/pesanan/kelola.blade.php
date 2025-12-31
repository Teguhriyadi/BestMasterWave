@extends('pages.layouts.app')

@push('title_module', 'List Data Shopee Pesanan')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Pesanan Shopee
    </h1>

    <a href="{{ url('/admin-panel/shopee/pesanan') }}" class="btn btn-danger btn-sm mb-4">
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
            <h6 class="m-0 font-weight-bold text-primary">List Data Pesanan</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_by">Filter Kolom Dengan</label>
                            <select name="filter_by" class="form-control" id="filter_by">
                                <option value="">- Pilih -</option>
                                <option value="waktu_pesanan_dibuat"
                                    {{ request('filter_by') == 'waktu_pesanan_dibuat' ? 'selected' : '' }}>
                                    Waktu Pesanan Dibuat
                                </option>
                                <option value="tanggal_pembayaran_dilakukan"
                                    {{ request('filter_by') == 'tanggal_pembayaran_dilakukan' ? 'selected' : '' }}>
                                    Tanggal Pembayaran Dilakukan
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="dari">Dari</label>
                            <input type="date" class="form-control" name="dari" value="{{ request('dari') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sampai">Sampai</label>
                            <input type="date" class="form-control" name="sampai" value="{{ request('sampai') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="fa fa-search"></i> Filter Data
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <hr>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>No. Pesanan</th>
                            <th>No. Referensi SKU</th>
                            <th>Nama Produk</th>
                            <th class="text-center">Waktu Pesanan Dibuat</th>
                            <th class="text-center">Waktu Pembayaran Dilakukan</th>
                            <th class="text-center">Status Pesanan</th>
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
                                <td>{{ $item->nomor_referensi_sku }}</td>
                                <td>{{ $item->nama_produk }}</td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($item->waktu_pesanan_dibuat)->translatedFormat('d F Y H:i:s') }}
                                </td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($item->waktu_pembayaran_dilakukan)->translatedFormat('d F Y H:i:s') }}
                                </td>
                                <td class="text-center">{{ $item->status_pesanan }}</td>
                                <td class="text-center">
                                    <a href="{{ url('/admin-panel/shopee/pesanan/data/' . $item->uuid . '/detail') }}" class="btn btn-info btn-sm">
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
