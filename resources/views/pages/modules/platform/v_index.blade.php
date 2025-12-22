@extends('pages.layouts.app')

@push('title_module', 'Platform')

@push('css_style')
    <link href="{{ url('/templating') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Platform
    </h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session("error"))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                <i class="fa fa-plus"></i> Tambah Data
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>Nama</th>
                            <th>Slug</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $nomer = 0
                        @endphp
                        @foreach ($platform as $item)
                        <tr>
                            <td class="text-center">{{ ++$nomer }}.</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->slug }}</td>
                            <td class="text-center">
                                @if ($item->status == "1")
                                <span class="badge bg-success text-white">
                                    Aktif
                                </span>
                                @elseif ($item->status == "0")
                                <span class="badge bg-danger text-white">
                                    Non - Aktif
                                </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="" method="POST" style="display: inline">
                                    @csrf
                                    @method("DELETE")
                                    <button onclick="return confirm('Yakin ? Ingin Menghapus Data Ini?')" type="submit" class="btn btn-danger btn-sm">
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
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="exampleModalLabel">
                        <i class="fa fa-plus"></i> Tambah Data
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('/admin-panel/platform') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama" class="form-label"> Nama Platform </label>
                            <input type="text" class="form-control" name="platform" id="platform" placeholder="Masukkan Nama Platform">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary btn-sm" data-dismiss="modal">
                            <i class="fa fa-times"></i> Batalkan
                        </button>
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('js_style')
    <script src="{{ url('/templating') }}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ url('/templating') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="{{ url('/templating') }}/js/demo/datatables-demo.js"></script>
@endpush
