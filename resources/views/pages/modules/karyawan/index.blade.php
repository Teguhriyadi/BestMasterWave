@extends('pages.layouts.app')

@push('title_module', 'Karyawan')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Karyawan
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
        <div class="card-header py-3">
            <a href="{{ url('/admin-panel/karyawan/create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Tambah Data
            </a>
        </div>
        <div class="card-body">
            <table class="table table-bordered nowrap" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th>ID Sidik Jari</th>
                        <th>No. KTP</th>
                        <th>Nama Karyawan</th>
                        <th class="text-center">Tanggal Masuk</th>
                        <th>No. Handphone</th>
                        <th>No. Handphone Darurat</th>
                        <th>Jenis Kelamin</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $nomer = 0
                    @endphp
                    @foreach ($karyawan as $item)
                        <tr>
                            <td class="text-center">{{ ++$nomer }}.</td>
                            <td>{{ $item['sidik_jari'] }}</td>
                            <td>{{ $item['no_ktp'] }}</td>
                            <td>{{ $item['nama'] }}</td>
                            <td class="text-center">{{ $item['tanggal_masuk'] }}</td>
                            <td>{{ $item['no_hp'] }}</td>
                            <td>{{ $item['no_hp_darurat'] }}</td>
                            <td class="text-center">{{ $item['jenis_kelamin'] }}</td>
                            <td class="text-center">
                                <a href="{{ url('/admin-panel/karyawan/' . $item['id'] . '/show') }}" class="btn btn-info btn-sm">
                                    <i class="fa fa-search"></i> Detail
                                </a>
                                <a href="{{ url('/admin-panel/karyawan/' . $item['id'] . '/edit') }}" class="btn btn-warning btn-sm">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                <form action="{{ url('/admin-panel/karyawan/' . $item['id']) }}" method="POST" style="display: inline">
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

    <!-- Modal Tambah -->
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
                <form action="{{ url('/admin-panel/bank') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_bank" class="form-label">
                                Nama Bank
                                <small class="text-danger">*</small>
                            </label>
                            <input type="text" class="form-control @error('nama_bank') is-invalid @enderror" name="nama_bank" id="nama_bank" placeholder="Masukkan Nama Bank" value="{{ old('nama_bank') }}">
                            @error('nama_bank')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="alias" class="form-label">
                                Nama Alias Bank
                                <small class="text-danger">*</small>
                            </label>
                            <input type="text" class="form-control @error('alias') is-invalid @enderror" name="alias" id="alias" placeholder="Masukkan Nama Alias Bank" value="{{ old('alias') }}">
                            @error('alias')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
    <!-- End Modal Tambah -->

    <!-- Modal Edit -->
    <div class="modal fade" id="exampleModalEdit" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="exampleModalLabel">
                        <i class="fa fa-edit"></i> Edit Data
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modal-content-edit">

                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Edit -->
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

        function editSupplier(id) {
            $.ajax({
                url: "{{ url('/admin-panel/bank') }}" + "/" + id + "/edit",
                type: "GET",
                success: function(response) {
                    $("#modal-content-edit").html(response)
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    </script>
@endpush
