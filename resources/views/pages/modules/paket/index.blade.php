@extends('pages.layouts.app')

@push('title_module', 'Paket')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Paket
    </h1>

    @if (session('success'))
        <div class="alert alert-success">
            <strong>Berhasil,</strong> {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            <strong>Gagal,</strong> {{ session('error') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        @if (canPermission('paket.create'))
            <div class="card-header py-3">
                <a href="{{ url('/admin-panel/paket/create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Tambah Data
                </a>
            </div>
        @endif
        <div class="card-body">
            <table class="table table-bordered nowrap" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th>SKU Paket</th>
                        <th>Nama Paket</th>
                        <th>Komponen Paket</th>
                        <th>Harga Jual</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $nomer = 0;
                    @endphp
                    @foreach ($paket as $item)
                        <tr>
                            <td class="text-center">{{ ++$nomer }}.</td>
                            <td>{{ $item->sku }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>
                                <ul>
                                    @foreach ($item->items as $p)
                                        <li>
                                            {{ $p->sku_barang }} (Qty : {{ $p->qty }})
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $item->harga_display }}</td>
                            <td class="text-center">
                                @if (canPermission('paket.show'))
                                    <a href="{{ url('/admin-panel/paket/' . $item->id . '/show') }}"
                                        class="btn btn-info btn-sm">
                                        <i class="fa fa-search"></i> Detail
                                    </a>
                                @endif
                                @if (canPermission('paket.edit'))
                                    <a href="{{ url('/admin-panel/paket/' . $item->id . '/edit') }}"
                                        class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                @endif
                                @if (canPermission('paket.delete'))
                                    <form action="{{ url('/admin-panel/paket/' . $item->id) }}" method="POST"
                                        style="display: inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Yakin ? Ingin Menghapus Data Ini?')" type="submit"
                                            class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                @endif

                                @if (!canPermission('paket.edit') && !canPermission('paket.delete') && !canPermission('paket.show'))
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
