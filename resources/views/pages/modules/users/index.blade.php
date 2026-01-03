@extends('pages.layouts.app')

@push('title_module', 'Users')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Users
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
        <div class="card-header py-3">
            <a href="{{ url('/admin-panel/users/create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Tambah Data
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $nomer = 0;
                        @endphp
                        @foreach ($users as $item)
                            <tr>
                                <td class="text-center">{{ ++$nomer }}.</td>
                                <td>{{ $item['nama'] }}</td>
                                <td>{{ $item['username'] }}</td>
                                <td>{{ $item['email'] }}</td>
                                <td class="text-center">
                                    @if ($item['status'] == "Aktif")
                                        <form action="{{ url('/admin-panel/users/' . $item['id'] . '/change-status') }}" method="POST">
                                            @csrf
                                            @method("PUT")
                                            <button onclick="return confirm('Yakin ? Ingin Menon-Aktifkan Akun Ini?')" type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa fa-times"></i> Non - Aktifkan
                                            </button>
                                        </form>
                                    @elseif($item['status'] == "Tidak Aktif")
                                        <form action="{{ url('/admin-panel/users/' . $item['id'] . '/change-status') }}" method="POST">
                                            @csrf
                                            @method("PUT")
                                            <button onclick="return confirm('Yakin ? Ingin Mengaktifkan Akun Ini?')" type="submit" class="btn btn-success btn-sm">
                                                <i class="fa fa-check"></i> Aktifkan
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ url('/admin-panel/users/' . $item['id'] . '/edit') }}"
                                        class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ url('/admin-panel/users/' . $item['id']) }}" method="POST"
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
    </div>
@endpush

@push('js_style')
    <script src="{{ asset('templating/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('templating/js/demo/datatables-demo.js') }}"></script>
@endpush
