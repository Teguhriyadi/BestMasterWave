@extends('pages.layouts.app')

@push('title_module', 'Permissions')

@push('css_style')
    <style>
        .permission-item {
            cursor: pointer;
            border: 1px solid #e3e6f0;
            transition: all .15s ease-in-out;
            background: #fff;
        }

        .permission-item:hover {
            background: #f8f9fc;
        }

        .permission-item input[type="checkbox"] {
            transform: scale(1.1);
        }

        .permission-item.active {
            background: #e8f4ff;
            border-color: #36b9cc;
        }
    </style>
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

    <a href="{{ url('/admin-panel/role-permissions') }}" class="btn btn-danger btn-sm">
        <i class="fa fa-sign-out-alt"></i> Kembali
    </a>

    <div class="card shadow mb-4 mt-3">
        <form action="{{ url('/admin-panel/role-permissions') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Role</h6>
                            </div>
                            <div class="card-body">
                                @foreach ($akses as $item)
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" id="role_{{ $item['id'] }}" name="role_id"
                                            class="custom-control-input" value="{{ $item['id'] }}"
                                            {{ request('role_id') == $item['id'] ? 'checked' : '' }}>
                                        <label class="custom-control-label"
                                            for="role_{{ $item['id'] }}">{{ $item['nama_role'] }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-info">
                                    <i class="fa fa-key mr-2"></i> Permission
                                </h6>
                            </div>

                            <div class="card-body">
                                @foreach ($grouping as $menu)
                                    @if ($menu->permissions->count())
                                        <div class="card border-left-info mb-4">
                                            <div class="card-body pb-2">

                                                <h6 class="font-weight-bold text-dark mb-3 d-flex align-items-center">
                                                    <i class="fa fa-folder-open text-info mr-2"></i>
                                                    {{ $menu->nama_menu }}
                                                </h6>

                                                <div class="row">
                                                    @foreach ($menu->permissions as $permission)
                                                        <div class="col-md-4 col-sm-6 mb-3">
                                                            <label
                                                                class="permission-item d-flex align-items-center p-2 rounded
                                            {{ in_array($permission->id, $selectedPermissions ?? []) ? 'active' : '' }}"
                                                                for="perm_{{ $permission->id }}">

                                                                <input type="checkbox" class="mr-3"
                                                                    id="perm_{{ $permission->id }}" name="permission_ids[]"
                                                                    value="{{ $permission->id }}"
                                                                    {{ in_array($permission->id, $selectedPermissions ?? []) ? 'checked' : '' }}>

                                                                <span class="text-dark">
                                                                    {{ $permission->nama }}
                                                                </span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>

                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="reset" class="btn btn-secondary btn-sm">
                    <i class="fa fa-times"></i> Batalkan
                </button>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
@endpush

@push('js_style')
    <script type="text/javascript">
        document.querySelectorAll('input[name="role_id"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    window.location.href =
                        "{{ url('/admin-panel/role-permissions/create') }}?role_id=" + this.value;
                }
            });
        });
    </script>
@endpush
