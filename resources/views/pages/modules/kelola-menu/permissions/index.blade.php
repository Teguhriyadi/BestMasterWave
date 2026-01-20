@extends('pages.layouts.app')

@push('title_module', 'Permissions')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Permissions
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
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                <i class="fa fa-plus"></i> Tambah Data
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered nowrap" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th>Nama Permissions</th>
                        <th>Akses</th>
                        <th>Tipe Menu</th>
                        <th>Nama Menu</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $nomer = 0;
                    @endphp
                    @foreach ($permissions as $item)
                        <tr>
                            <td class="text-center">{{ ++$nomer }}.</td>
                            <td>{{ $item['nama'] }}</td>
                            <td>{{ $item['akses'] }}</td>
                            <td>{{ $item['tipe'] }}</td>
                            <td>{{ $item['menu'] }}</td>
                            <td class="text-center">
                                <button onclick="editPermissions('{{ $item['id'] }}')" type="button"
                                    class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModalEdit">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <form action="{{ url('/admin-panel/permissions/' . $item['id']) }}" method="POST"
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

    <!-- Modal Tambah -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="exampleModalLabel">
                        <i class="fa fa-plus"></i> Tambah Data
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('/admin-panel/permissions') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama" class="form-label">
                                Nama Modul
                                <small class="text-danger">*</small>
                            </label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama"
                                id="nama" placeholder="Masukkan Nama Modul" value="{{ old('nama') }}">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="akses" class="form-label">
                                        Akses <small>(ex : platform)</small>
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="text" class="form-control @error('akses') is-invalid @enderror"
                                        name="akses" id="akses" placeholder="Masukkan Akses"
                                        value="{{ old('akses') }}">
                                    @error('akses')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="akses" class="form-label">
                                        Tipe Akses
                                        <small class="text-danger">*</small>
                                    </label>
                                    <select name="tipe_akses"
                                        class="form-control @error('tipe_akses') is-invalid @enderror"
                                        id="tipe_akses">
                                        <option value="">- Pilih Tipe -</option>
                                        <option {{ old('tipe_akses') == "read" ? 'selected' : '' }} value="read">Read (Baca)</option>
                                        <option {{ old('tipe_akses') == "create" ? 'selected' : '' }} value="create">Create (Tambah)</option>
                                        <option {{ old('tipe_akses') == "edit" ? 'selected' : '' }} value="edit">Update (Ubah)</option>
                                        <option {{ old('tipe_akses') == "delete" ? 'selected' : '' }} value="delete">Delete (Hapus)</option>
                                        <option {{ old('tipe_akses') == "show" ? 'selected' : '' }} value="show">Show (Detail)</option>
                                        <option {{ old('tipe_akses') == "change_status" ? 'selected' : '' }} value="change_status">Change Status (Ubah Status)</option>
                                    </select>
                                    @error('tipe_akses')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="menu_id" class="form-label">
                                Nama Menu
                                <small class="text-danger">*</small>
                            </label>
                            <select name="menu_id" class="form-control @error('menu_id') is-invalid @enderror"
                                id="menu_id">
                                <option value="">- Pilih -</option>
                                @foreach ($menu as $item)
                                    <option value="{{ $item['id'] }}">
                                        {{ $item['nama_menu'] }}
                                    </option>
                                @endforeach
                            </select>

                            @error('menu_id')
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
        <div class="modal-dialog modal-lg">
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#menu_id').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#exampleModal')
            });

            $('#dataTable').DataTable({
                scrollX: true,
                autoWidth: false,
                responsive: false
            });
        });

        function editPermissions(id) {
            $.ajax({
                url: "{{ url('/admin-panel/permissions') }}" + "/" + id + "/edit",
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
