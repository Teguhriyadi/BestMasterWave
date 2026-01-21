@extends('pages.layouts.app')

@push('title_module', 'Divisi Role')

@push('css_style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet">

    <style>
        .role-checkbox {
            cursor: pointer;
            border: 1px solid #e3e6f0;
            background: #fff;
            transition: all .15s ease-in-out;
            display: flex;
            align-items: center;
            min-height: 44px;
        }

        .role-checkbox:hover {
            background: #f8f9fc;
        }

        .role-checkbox input[type="checkbox"] {
            transform: scale(1.1);
        }

        .role-checkbox.active {
            background: #e8f4ff;
            border-color: #36b9cc;
        }
    </style>
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

                <div class="form-group mb-4">
                    <label for="divisi_id" class="font-weight-bold">Nama Divisi</label>

                    @if (!empty(Auth::user()->one_divisi_roles))
                        <input type="hidden" name="divisi_id" value="{{ Auth::user()->one_divisi_roles->divisi->id }}">

                        <input type="text" class="form-control"
                            value="{{ Auth::user()->one_divisi_roles->divisi->nama_divisi }}" readonly>
                    @else
                        <select name="divisi_id" class="form-control" id="divisi_id">
                            <option value="">- Pilih -</option>
                            @foreach ($divisi as $item)
                                <option value="{{ $item['id'] }}">
                                    {{ $item['nama_divisi'] }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="form-group mb-3">
                    <label class="font-weight-bold mb-3">
                        Role Yang Diizinkan
                    </label>

                    <div class="row">
                        @foreach ($role as $item)
                            <div class="col-md-3 col-sm-6 mb-3">
                                <label
                                    class="role-checkbox p-2 rounded
                                {{ in_array($item['id'], old('roles', [])) ? 'active' : '' }}"
                                    for="role_{{ $item['id'] }}">

                                    <input type="checkbox" name="roles[]" id="role_{{ $item['id'] }}"
                                        value="{{ $item['id'] }}" class="mr-2">

                                    <span class="text-dark">
                                        {{ $item['nama_role'] }}
                                    </span>
                                </label>
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

            $('#divisi_id').on('change', function() {
                loadDivisiRoles($(this).val());
            });
        });

        function loadDivisiRoles(divisiId) {
            console.log('loadDivisiRoles dipanggil dengan ID:', divisiId); // ✅

            document.querySelectorAll('input[name="roles[]"]').forEach(cb => {
                cb.checked = false;
                cb.closest('.role-checkbox').classList.remove('active');
            });

            if (!divisiId) return;

            fetch(`{{ url('admin-panel/divisi-role') }}/${divisiId}/roles`)
                .then(res => res.json())
                .then(res => {
                    if (!res.status) return;

                    res.roles.forEach(roleId => {
                        const el = document.getElementById(`role_${roleId}`);
                        if (el) {
                            el.checked = true;
                            el.closest('.role-checkbox').classList.add('active');
                        }
                    });
                })
                .catch(() => {
                    alert('Gagal memuat role divisi');
                });
        }

        @if (!empty(Auth::user()->one_divisi_roles))
            loadDivisiRoles({{ Auth::user()->one_divisi_roles->divisi->id }});
        @endif

        document.querySelectorAll('input[name="roles[]"]').forEach(cb => {
            cb.addEventListener('change', function() {
                this.closest('.role-checkbox')
                    .classList.toggle('active', this.checked);
            });
        });
    </script>

@endpush
