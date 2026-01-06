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
        <div class="card-header py-3">
            <a href="{{ url('/admin-panel/pembelian/create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Tambah Data
            </a>
        </div>
        <div class="card-body">
            <table class="table table-bordered nowrap" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">No. Invoice</th>
                        <th class="text-center">Tanggal Invoice</th>
                        <th class="text-center">Tanggal Jatuh Tempo</th>
                        <th class="text-center">Total Harga</th>
                        <th class="text-center">Total Diskon</th>
                        <th class="text-center">Total PPN</th>
                        <th>Nama Supplier</th>
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
                            <td class="text-center">{{ $item['no_invoice'] }}</td>
                            <td class="text-center">{{ $item['tanggal_invoice'] }}</td>
                            <td class="text-center">{{ $item['tanggal_jatuh_tempo'] }}</td>
                            <td class="text-center">{{ $item['total_harga'] }}</td>
                            <td class="text-center">{{ $item['total_diskon'] }}</td>
                            <td class="text-center">{{ $item['total_ppn'] }}</td>
                            <td>{{ $item['supplier'] }}</td>
                            <td>{{ $item['keterangan'] }}</td>
                            <td class="text-center">
                                <a href="{{ url('/admin-panel/pembelian/' . $item['id'] . '/edit') }}" class="btn btn-warning btn-sm">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                <form action="{{ url('/admin-panel/pembelian/' . $item['id']) }}" method="POST"
                                    style="display: inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Yakin ? Ingin Menghapus Data Ini?')" type="submit"
                                        class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </form>
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
