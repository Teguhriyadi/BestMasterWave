@extends('pages.layouts.app')

@push('title_module', 'Divisi Role')

@push('css_style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Tambah / Edit Data Divisi Role
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
            <a href="{{ url('/admin-panel/divisi-role') }}" class="btn btn-danger btn-sm">
                <i class="fa fa-sign-out-alt"></i> Kembali
            </a>
        </div>
        <form action="{{ url('/admin-panel/divisi-role') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="divisi_id" class="form-label">Nama Divisi</label>
                    <select name="divisi_id" class="form-control" id="divisi_id">
                        <option value="">- Pilih -</option>
                        @foreach ($divisi as $item)
                            <option value="{{ $item['id'] }}">
                                {{ $item['nama_divisi'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="fw-bold">Role Yang Diizinkan</label>
                    <div class="row">
                        @foreach ($role as $item)
                            <div class="col-md-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="roles[]" class="custom-control-input"
                                        value="{{ $item['id'] }}" id="role_{{ $item['id'] }}">
                                    <label class="custom-control-label" for="role_{{ $item['id'] }}">
                                        {{ $item['nama_role'] }}
                                    </label>
                                </div>
                            </div>
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
@endpush

@push('js_style')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#divisi_id').select2({
                theme: 'bootstrap4'
            });
        })

        document.getElementById('divisi_id').addEventListener('change', function() {

            const divisiId = this.value;

            document.querySelectorAll('input[name="roles[]"]').forEach(cb => {
                cb.checked = false;
            });

            if (!divisiId) return;

            fetch(`{{ url('admin-panel/divisi-role/${divisiId}/roles') }}`)
                .then(res => res.json())
                .then(res => {
                    if (!res.status) return;

                    res.roles.forEach(roleId => {
                        const el = document.getElementById(`role_${roleId}`);
                        if (el) el.checked = true;
                    });
                })
                .catch(() => {
                    alert('Gagal memuat role divisi');
                });
        });
    </script>
@endpush
