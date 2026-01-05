@extends('pages.layouts.app')

@push('title_module', 'Users')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Tambah Data Users
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

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <a href="{{ url('/admin-panel/users') }}" class="btn btn-danger btn-sm">
                        <i class="fa fa-sign-out-alt"></i> Kembali
                    </a>
                </div>
                <form action="{{ url('/admin-panel/users') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama">
                                        Nama
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" id="nama"
                                        placeholder="Masukkan Nama" value="{{ old('nama') }}">
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username">
                                        Username
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username"
                                        placeholder="Masukkan Username" value="{{ old('username') }}">
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">
                                        Email
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email"
                                        placeholder="Masukkan Email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">
                                        Nomor Handphone
                                    </label>
                                    <input type="text" class="form-control @error('nomor_handphone') is-invalid @enderror" name="nomor_handphone" id="nomor_handphone"
                                        placeholder="Masukkan Nomor Handphone" value="{{ old('nomor_handphone') }}">
                                    @error('nomor_handphone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="divisi_id" class="form-label">
                                        Nama Divisi
                                        <small class="text-danger">*</small>
                                    </label>
                                    <select name="divisi_id" class="form-control @error('divisi_id') is-invalid @enderror" id="divisi_id">
                                        <option value="">- Pilih -</option>
                                        @foreach ($divisi as $item)
                                            <option value="{{ $item['id'] }}">
                                                {{ $item['nama_divisi'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('divisi_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role_id" class="form-label">
                                        Nama Role
                                        <small class="text-danger">*</small>
                                    </label>
                                    <select name="role_id" class="form-control @error('role_id') is-invalid @enderror" id="role_id">
                                        <option value="">- Pilih Role -</option>
                                    </select>
                                    @error('role_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="alamat" class="form-label">
                                Alamat
                            </label>
                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" id="alamat" rows="5" placeholder="Masukkan Alamat">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="reset" class="btn btn-secondary btn-sm">
                            <i class="fa fa-times"></i> Batal
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#divisi_id').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Divisi',
                allowClear: true,
                width: '100%'
            });

            $('#role_id').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Role',
                allowClear: true,
                width: '100%'
            });
        });

        $('#divisi_id').on('change', function() {
            const divisiId = $(this).val();
            const roleSelect = $('#role_id');

            roleSelect.empty().trigger('change');

            if (!divisiId) return;

            fetch(`/admin-panel/divisi/${divisiId}/roles`)
                .then(res => res.json())
                .then(res => {
                    if (!res.status) return;

                    res.roles.forEach(role => {
                        const option = new Option(
                            role.name,
                            role.id,
                            false,
                            false
                        );
                        roleSelect.append(option);
                    });

                    roleSelect.trigger('change');
                })
                .catch(() => {
                    alert('Gagal memuat role berdasarkan divisi');
                });
        });
    </script>
@endpush
