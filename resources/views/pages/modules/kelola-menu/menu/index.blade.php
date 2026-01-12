@extends('pages.layouts.app')

@push('title_module', 'Menu')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Menu
    </h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Gagal!</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                <i class="fa fa-plus"></i> Tambah Data
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>Tipe Menu</th>
                            <th>Nama Menu</th>
                            <th>Slug</th>
                            <th>URL Menu</th>
                            <th>Ikon</th>
                            <th>Parent Menu</th>
                            <th class="text-center">Urutan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $nomer = 0;
                        @endphp
                        @foreach ($menu as $item)
                            <tr>
                                <td class="text-center">{{ ++$nomer }}.</td>
                                <td>
                                    @if ($item['type'] == 'header')
                                        <span class="badge bg-success text-white text-uppercase">
                                            Header
                                        </span>
                                    @elseif($item['type'] == 'menu')
                                        <span class="badge bg-primary text-white text-uppercase">
                                            Menu
                                        </span>
                                    @elseif ($item['type'] == 'submenu')
                                        <span class="badge bg-warning text-white text-uppercase">
                                            Sub Menu
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $item['nama_menu'] }}</td>
                                <td>{{ $item['slug'] }}</td>
                                <td>{{ $item['url_menu'] }}</td>
                                <td>{{ $item['ikon'] }}</td>
                                <td>{{ $item['parent_menu'] }}</td>
                                <td class="text-center">{{ $item['order'] }}</td>
                                <td class="text-center">
                                    @if ($item['status'] == 'Aktif')
                                        <span class="badge bg-success text-white text-uppercase">
                                            Aktif
                                        </span>
                                    @elseif($item['status'] == 'Tidak Aktif')
                                        <span class="badge bg-danger text-white text-uppercase">
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button onclick="editMenu(`{{ $item['id'] }}`)" type="button"
                                        class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModalEdit">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                    <form action="{{ url('/admin-panel/menu/' . $item['id']) }}" method="POST"
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
                <form action="{{ url('/admin-panel/menu') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_menu" class="form-label">
                                Nama Menu
                                <small class="text-danger">*</small>
                            </label>
                            <input type="text" class="form-control @error('nama_menu') is-invalid @enderror"
                                name="nama_menu" id="nama_menu" placeholder="Masukkan Nama Menu"
                                value="{{ old('nama_menu') }}">
                            @error('nama_menu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tipe_menu" class="form-label">
                                Tipe Menu
                                <small class="text-danger">*</small>
                            </label>
                            <select name="tipe_menu" class="form-control tipe-menu @error("tipe_menu") is-invalid @enderror">
                                <option value="">- Pilih -</option>
                                <option {{ old('tipe_menu') == "header" ? 'selected' : '' }} value="header">Header</option>
                                <option {{ old('tipe_menu') == "menu" ? 'selected' : '' }} value="menu">Menu</option>
                                <option {{ old('tipe_menu') == "submenu" ? 'selected' : '' }} value="submenu">Sub Menu</option>
                            </select>
                            @error('tipe_menu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="parent-headers-wrap" style="display:none">
                            <div class="form-group">
                                <label class="form-label">Nama Header Menu <small class="text-danger">*</small></label>
                                <select name="parent_id_header" class="form-control select-parent" id="select_header">
                                    <option value="">- Pilih Header -</option>
                                    @foreach ($headers as $header)
                                        <option value="{{ $header['id'] }}">{{ $header['nama_menu'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="parent-wrap" style="display:none">
                            <div class="form-group">
                                <label class="form-label">Nama Parent Menu <small class="text-danger">*</small></label>
                                <select name="parent_id_menu" class="form-control select-parent" id="select_menu">
                                    <option value="">- Pilih Parent -</option>
                                    @foreach ($parents as $parent)
                                        <option value="{{ $parent['id'] }}">{{ $parent['nama_menu'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group url-wrap">
                            <label for="url" class="form-label">
                                URL Menu
                                <small class="text-danger">*</small>
                            </label>
                            <input type="text" name="url" class="form-control" id="url"
                                placeholder="Masukkan URL Menu" value="{{ old('url') }}">
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="icon" class="form-label">
                                Ikon Menu
                                <small class="text-danger">*</small>
                            </label>
                            <input type="text" class="form-control @error('icon') is-invalid @enderror" name="icon"
                                id="icon" placeholder="Masukkan Ikon Menu" value="{{ old('icon') }}">
                            @error('icon')
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
    <!-- Modal End -->

    <!-- Modal Tambah -->
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
    <!-- Modal End -->
@endpush

@push('js_style')
    <script src="{{ asset('templating/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('templating/js/demo/datatables-demo.js') }}"></script>

    <script type="text/javascript">
        function editMenu(id) {
            $.ajax({
                url: "{{ url('/admin-panel/menu') }}" + "/" + id + "/edit",
                type: "GET",
                success: function(response) {
                    $("#modal-content-edit").html(response)
                    toggleMenuField(document.getElementById('modal-content-edit'));
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function toggleMenuField(container) {
            const typeSelect = container.querySelector('.tipe-menu');
            const parentWrap = container.querySelector('.parent-wrap');
            const parentHeadersWrap = container.querySelector('.parent-headers-wrap');
            const urlWrap = container.querySelector('.url-wrap');

            const selectHeader = container.querySelector('#select_header');
            const selectMenu = container.querySelector('#select_menu');

            if (!typeSelect) return;

            function toggle() {
                const type = typeSelect.value;

                if (parentWrap) parentWrap.style.display = 'none';
                if (parentHeadersWrap) parentHeadersWrap.style.display = 'none';
                if (urlWrap) urlWrap.style.display = 'block';

                if (selectHeader) selectHeader.removeAttribute('name');
                if (selectMenu) selectMenu.removeAttribute('name');

                if (type === 'header') {
                    if (urlWrap) urlWrap.style.display = 'none';
                }

                if (type === 'menu') {
                    if (parentHeadersWrap) parentHeadersWrap.style.display = 'block';
                    if (selectHeader) selectHeader.setAttribute('name', 'parent_id');
                }

                if (type === 'submenu') {
                    if (parentWrap) parentWrap.style.display = 'block';
                    if (selectMenu) selectMenu.setAttribute('name', 'parent_id');
                }
            }

            toggle();
            typeSelect.addEventListener('change', toggle);
        }

        toggleMenuField(document);

        // $(document).on('shown.bs.modal', '#exampleModalEdit', function() {
        //     toggleMenuField(this);
        // });
    </script>
@endpush
