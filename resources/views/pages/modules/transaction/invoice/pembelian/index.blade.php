@extends('pages.layouts.app')

@push('title_module', 'Pembelian')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Pembelian
    </h1>

    @if (session('success'))
        <div class="alert alert-success">
            <strong>Berhasil</strong>, {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            <strong>Gagal</strong>, {{ session('error') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        @if (canPermission('pembelian.create'))
            <div class="card-header py-3">
                <a href="{{ url('/admin-panel/pembelian/create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Tambah Data
                </a>
            </div>
        @endif
        <div class="card-body">
            <form method="GET" action="{{ url('/admin-panel/pembelian') }}">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="supplier_id"> Nama Supplier </label>
                            <select name="supplier_id" class="form-control" id="supplier_id">
                                <option value="">- Pilih Supplier -</option>
                                @foreach ($supplier as $item)
                                    <option {{ request('supplier_id') == $item['id'] ? 'selected' : '' }}
                                        value="{{ $item['id'] }}">
                                        {{ $item['nama_supplier'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tgl_invoice_dari" class="form-label"> Tgl Invoice Dari </label>
                            <input type="date" class="form-control" name="tgl_invoice_dari" id="tgl_invoice_dari"
                                value="{{ request('tgl_invoice_dari') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tgl_invoice_sampai" class="form-label"> Tgl Invoice Sampai </label>
                            <input type="date" class="form-control" name="tgl_invoice_sampai" id="tgl_invoice_sampai"
                                value="{{ request('tgl_invoice_sampai') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tgl_jatuh_tempo_dari" class="form-label"> Tgl Jatuh Tempo Dari </label>
                            <input type="date" class="form-control" name="tgl_jatuh_tempo_dari" id="tgl_jatuh_tempo_dari"
                                value="{{ request('tgl_jatuh_tempo_dari') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tgl_jatuh_tempo_sampai" class="form-label"> Tgl Jatuh Tempo Sampai </label>
                            <input type="date" class="form-control" name="tgl_jatuh_tempo_sampai"
                                id="tgl_jatuh_tempo_sampai" value="{{ request('tgl_jatuh_tempo_sampai') }}">
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
            <table class="table table-bordered nowrap" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th>Nama Supplier</th>
                        @if (empty(Auth::user()->one_divisi_roles))
                            <th>Divisi</th>
                        @endif
                        <th class="text-center">No. Invoice</th>
                        <th class="text-center">Tanggal Invoice</th>
                        <th class="text-center">Tanggal Jatuh Tempo</th>
                        <th class="text-center">Total Harga</th>
                        <th class="text-center">Total Diskon</th>
                        <th class="text-center">Total PPN</th>
                        <th>Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $nomer = 0;
                    @endphp
                    @foreach ($pembelian as $item)
                        <tr>
                            <td class="text-center">{{ ++$nomer }}.</td>
                            <td>{{ $item['supplier'] }}</td>
                            @if (empty(Auth::user()->one_divisi_roles))
                                <td>{{ $item['divisi'] }}</td>
                            @endif
                            <td class="text-center">{{ $item['no_invoice'] }}</td>
                            <td class="text-center">{{ $item['tanggal_invoice'] }}</td>
                            <td class="text-center">{{ $item['tanggal_jatuh_tempo'] }}</td>
                            <td class="text-center">{{ $item['total_harga'] }}</td>
                            <td class="text-center">{{ $item['total_diskon'] }}</td>
                            <td class="text-center">{{ $item['total_ppn'] }}</td>
                            <td>{{ $item['keterangan'] }}</td>
                            <td class="text-center">
                                @if (canPermission('pembelian.show'))
                                    <a href="{{ url('/admin-panel/pembelian/' . $item['id'] . '/detail') }}"
                                        class="btn btn-info btn-sm">
                                        <i class="fa fa-search"></i> Detail
                                    </a>
                                @endif
                                @if (canPermission('pembelian.edit'))
                                    <a href="{{ url('/admin-panel/pembelian/' . $item['id'] . '/edit') }}"
                                        class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                @endif
                                @if (canPermission('pembelian.delete'))
                                    <form action="{{ url('/admin-panel/pembelian/' . $item['id']) }}" method="POST"
                                        style="display: inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Yakin ? Ingin Menghapus Data Ini?')" type="submit"
                                            class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                @endif

                                @if (!canPermission('pembelian.edit') && !canPermission('pembelian.delete') && !canPermission('pembelian.show'))
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endpush

@push('js_style')
    <script src="{{ asset('templating/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#dataTable').DataTable({
                scrollX: true,
                autoWidth: false,
                responsive: false
            });
        });
    </script>
@endpush
