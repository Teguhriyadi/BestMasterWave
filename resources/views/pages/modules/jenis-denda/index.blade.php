@extends('pages.layouts.app')

@push('title_module', 'Jenis Denda')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Jenis Denda
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
        @if (canPermission('jenis-denda.create'))
            <div class="card-header py-3">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                    <i class="fa fa-plus"></i> Tambah Data
                </button>
            </div>
        @endif
        <div class="card-body">
            <table class="table table-bordered nowrap" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th class="text-center">Nominal</th>
                        <th>Keterangan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $nomer = 0;
                    @endphp
                    @foreach ($jenis_denda as $item)
                        <tr>
                            <td class="text-center">{{ ++$nomer }}.</td>
                            <td>{{ $item['kode'] }}</td>
                            <td>{{ $item['nama_jenis'] }}</td>
                            <td class="text-center">{{ $item['nominal'] }}</td>
                            <td>{{ $item['keterangan'] }}</td>
                            <td class="text-center">{!! $item['status'] !!}</td>
                            <td class="text-center">
                                @if (canPermission('jenis-denda.edit'))
                                    <button onclick="editJenisDenda('{{ $item['id'] }}')" type="button"
                                        class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModalEdit">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                @endif
                                @if (canPermission('jenis-denda.delete'))
                                    <form action="{{ url('/admin-panel/jenis-denda/' . $item['id']) }}" method="POST"
                                        style="display: inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Yakin ? Ingin Menghapus Data Ini?')" type="submit"
                                            class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                @endif

                                @if (!canPermission('jenis-denda.edit') && !canPermission('jenis-denda.delete'))
                                    -
                                @endif
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
                <form action="{{ url('/admin-panel/jenis-denda') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="kode" class="form-label">
                                Kode Denda
                                <small class="text-danger">*</small>
                            </label>
                            <input type="text" class="form-control @error('kode') is-invalid @enderror" name="kode"
                                id="kode" placeholder="Masukkan Kode Denda" value="{{ old('kode') }}">
                            @error('kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_jenis" class="form-label">
                                        Nama Jenis Denda
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="text" class="form-control @error('nama_jenis') is-invalid @enderror"
                                        name="nama_jenis" id="nama_jenis" placeholder="Masukkan Nama Jenis Denda"
                                        value="{{ old('nama_jenis') }}">
                                    @error('nama_jenis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nominal" class="form-label">
                                        Nominal Potongan
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="number" class="form-control @error('nominal') is-invalid @enderror"
                                        name="nominal" id="nominal" placeholder="Masukkan Nominal Potongan"
                                        value="{{ old('nominal') }}" min="0">
                                    @error('nominal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="form-label">
                                Keterangan
                                <small class="text-danger">*</small>
                            </label>
                            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" id="keterangan"
                                rows="5" placeholder="Masukkan Keterangan">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
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

    <script type="text/javascript">
        $(document).ready(function() {
            $('#dataTable').DataTable({
                scrollX: true,
                autoWidth: false,
                responsive: false
            });
        });

        function editJenisDenda(id) {
            $.ajax({
                url: "{{ url('/admin-panel/jenis-denda') }}" + "/" + id + "/edit",
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
