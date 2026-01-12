@extends('pages.layouts.app')

@push('title_module', 'Permissions')

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
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-info">Permission</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        @foreach ($grouping as $menu)
                                            @if ($menu->permissions->count())
                                                <div class="mb-3">
                                                    <h6 class="font-weight-bold text-dark mb-1">
                                                        {{ $menu->nama_menu }}
                                                    </h6>
                                                    <hr class="mt-2 mb-3">
                                                    <div class="row">
                                                        @foreach ($menu->permissions as $permission)
                                                            <div class="col-md-3">
                                                                <div class="custom-control custom-checkbox mb-2">
                                                                    <input type="checkbox" class="custom-control-input"
                                                                        id="perm_{{ $permission->id }}"
                                                                        name="permission_ids[]"
                                                                        value="{{ $permission->id }}"
                                                                        {{ in_array($permission->id, $selectedPermissions ?? []) ? 'checked' : '' }}>
                                                                    <label class="custom-control-label"
                                                                        for="perm_{{ $permission->id }}">
                                                                        {{ $permission->nama }}
                                                                    </label>
                                                                </div>

                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
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
