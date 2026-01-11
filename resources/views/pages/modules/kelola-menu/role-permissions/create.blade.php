@extends('pages.layouts.app')

@push('title_module', 'Permissions')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
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
            <a href="{{ url('/admin-panel/role-permissions') }}" class="btn btn-danger btn-sm">
                <i class="fa fa-sign-out-alt"></i> Kembali
            </a>
        </div>
        <form action="{{ url('/admin-panel/role-permissions') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="role_id"> Nama Role </label>
                    <select name="role_id" class="form-control" id="role_id">
                        <option value="">- Pilih -</option>
                        @foreach ($akses as $item)
                            <option value="{{ $item['id'] }}" {{ ($roleId ?? '') == $item['id'] ? 'selected' : '' }}>
                                {{ $item['nama_role'] }}
                            </option>
                        @endforeach
                    </select>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        @foreach ($grouping as $menu)
                            @if ($menu->permissions->count())
                                <div class="mb-3">
                                    <h6 class="fw-bold text-primary mb-2">
                                        {{ $menu->nama_menu }}
                                    </h6>

                                    <div class="row">
                                        @foreach ($menu->permissions as $permission)
                                            <div class="col-md-4 mb-1">
                                                <label class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permission_ids[]"
                                                        value="{{ $permission->id }}"
                                                        {{ in_array($permission->id, $selectedPermissions ?? []) ? 'checked' : '' }}>
                                                    <span class="form-check-label">
                                                        {{ $permission->nama }}
                                                        <small class="text-muted">
                                                            ({{ $permission->akses }})
                                                        </small>
                                                    </span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <hr class="my-2">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="reset" class="btn btn-secondary btn-sm">
                    <i class="fa fa-times"></i> Batalkan
                </button>
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </div>
        </form>
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
                        <div class="form-group">
                            <label for="akses" class="form-label">
                                Akses
                                <small class="text-danger">*</small>
                            </label>
                            <input type="text" class="form-control @error('akses') is-invalid @enderror" name="akses"
                                id="akses" placeholder="Masukkan Akses" value="{{ old('akses') }}">
                            @error('akses')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- <div class="form-group">
                            <label for="menu_id" class="form-label">
                                Nama Menu
                                <small class="text-danger">*</small>
                            </label>
                            <select name="menu_id" class="form-control @error('menu_id') is-invalid @enderror" id="menu_id">
                                <option value="">- Pilih -</option>
                                @foreach ($menu as $item)
                                    <option value="{{ $item['id'] }}">
                                        {{ $item["nama_menu"] }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}
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
    <div class="modal fade" id="exampleModalEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

        document.getElementById('role_id').addEventListener('change', function() {
            if (this.value) {
                window.location.href =
                    "{{ url('/admin-panel/role-permissions/create') }}?role_id=" + this.value;
            }
        });
    </script>
@endpush
