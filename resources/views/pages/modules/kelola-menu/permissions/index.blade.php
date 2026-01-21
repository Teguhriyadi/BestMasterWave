@extends('pages.layouts.app')

@push('title_module', 'Permissions')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet">

@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">Data Permissions</h1>

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
                    @php $nomer = 0; @endphp
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
                                    style="display:inline">
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
    <div class="modal fade" id="exampleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-plus"></i> Tambah Data</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <form action="{{ url('/admin-panel/permissions') }}" method="POST">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Nama Modul <small class="text-danger">*</small></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama') }}" placeholder="Masukkan Nama Modul">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Akses <small class="text-danger">*</small></label>
                                    <input type="text" name="akses"
                                        class="form-control @error('akses') is-invalid @enderror"
                                        value="{{ old('akses') }}" placeholder="Masukkan Nama Akses">
                                    @error('akses')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Menu <small class="text-danger">*</small></label>
                                    <select name="menu_id" id="menu_id" class="form-control select2">
                                        <option value="">- Pilih -</option>
                                        @foreach ($menu as $group)
                                            <optgroup label="{{ strtoupper($group['label']) }}">
                                                @foreach ($group['items'] as $item)
                                                    <option value="{{ $item['id'] }}"
                                                        data-header="{{ $group['label'] }}"
                                                        data-type="{{ $item['type'] }}">
                                                        {{ $item['text'] }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>

                                    @error('menu_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Tipe Akses <small class="text-danger">*</small></label>
                            @php $oldTipe = old('tipe_akses', []); @endphp
                            <select name="tipe_akses[]" id="tipe_akses" multiple
                                class="form-control @error('tipe_akses') is-invalid @enderror">

                                <option value="read" {{ in_array('read', $oldTipe) ? 'selected' : '' }}>Read</option>
                                <option value="create" {{ in_array('create', $oldTipe) ? 'selected' : '' }}>Create</option>
                                <option value="edit" {{ in_array('edit', $oldTipe) ? 'selected' : '' }}>Edit</option>
                                <option value="delete" {{ in_array('delete', $oldTipe) ? 'selected' : '' }}>Delete</option>
                                <option value="show" {{ in_array('show', $oldTipe) ? 'selected' : '' }}>Show</option>
                                <option value="change_status" {{ in_array('change_status', $oldTipe) ? 'selected' : '' }}>
                                    Change Status
                                </option>
                            </select>
                            @error('tipe_akses')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success btn-sm">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="exampleModalEdit" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-edit"></i> Edit Data</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div id="modal-content-edit"></div>
            </div>
        </div>
    </div>

@endpush

@push('js_style')
    <script src="{{ asset('templating/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#menu_id').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#exampleModal'),
                width: '100%'
            });

            $('#tipe_akses').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Tipe Akses',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#exampleModal')
            });

            $('#dataTable').DataTable({
                scrollX: true,
                autoWidth: false,
                responsive: false
            });
        });

        function initEditSelect2() {
            $('#menu_id_edit').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#exampleModalEdit'),
                width: '100%'
            });

            $('#tipe_akses_edit').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Tipe Akses',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#exampleModalEdit')
            });
        }

        function editPermissions(id) {
            $.ajax({
                url: "{{ url('/admin-panel/permissions') }}/" + id + "/edit",
                type: "GET",
                success: function(response) {
                    $("#modal-content-edit").html(response);
                    initEditSelect2();
                }
            });
        }
    </script>
@endpush
