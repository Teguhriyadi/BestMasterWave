@extends('pages.layouts.app')

@push('title_module', 'Setup Jam Kerja')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Setup Jam Kerja
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
        @if (canPermission('setup-jam-kerja.create'))
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
                        <th>Divisi</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th class="text-center">Toleransi Keterlambatan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $nomer = 0;
                    @endphp
                    @foreach ($setup_jam_kerja as $item)
                        <tr>
                            <td class="text-center">{{ ++$nomer }}.</td>
                            <td>{{ $item['divisi'] }}</td>
                            <td>{{ $item['jam_masuk'] }}</td>
                            <td>{{ $item['jam_pulang'] }}</td>
                            <td class="text-center">{{ $item['toleransi'] }} Menit</td>
                            <td class="text-center">
                                @if (canPermission('setup-jam-kerja.edit'))
                                    <button onclick="editSetupJamKerja('{{ $item['id'] }}')" type="button"
                                        class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModalEdit">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                @endif
                                @if (canPermission('setup-jam-kerja.delete'))
                                    <form action="{{ url('/admin-panel/setup-jam-kerja/' . $item['id']) }}" method="POST"
                                        style="display: inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Yakin ? Ingin Menghapus Data Ini?')" type="submit"
                                            class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i> Hapus
                                        </button>
                                    </form>
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
                <form action="{{ url('/admin-panel/setup-jam-kerja') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jam_masuk" class="form-label">
                                        Jam Masuk
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="time" class="form-control @error('jam_masuk') is-invalid @enderror"
                                        name="jam_masuk" id="jam_masuk"
                                        value="{{ old('jam_masuk') }}">
                                    @error('jam_masuk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jam_pulang" class="form-label">
                                        Jam Pulang
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="time" class="form-control @error('jam_pulang') is-invalid @enderror"
                                        name="jam_pulang" id="jam_pulang"
                                        value="{{ old('jam_pulang') }}">
                                    @error('jam_pulang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="toleransi_menit" class="form-label">
                                        Toleransi Keterlambatan
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="number" class="form-control @error('toleransi_menit') is-invalid @enderror"
                                        name="toleransi_menit" id="toleransi_menit" min="0" placeholder="0"
                                        value="{{ old('toleransi_menit') }}">
                                    @error('toleransi_menit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="divisi_id" class="form-label">
                                        Divisi
                                        <small class="text-danger">*</small>
                                    </label>
                                    <select name="divisi_id" class="form-control @error("divisi_id") is-invalid @enderror" id="divisi_id">
                                        <option value="">- Pilih -</option>
                                        @foreach ($divisi as $item)
                                            <option {{ old('divisi_id') == $item['id'] ? 'selected' : '' }} value="{{ $item['id'] }}">
                                                {{ $item['nama_divisi'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('divisi_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
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
    <div class="modal fade" id="exampleModalEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

        function editSetupJamKerja(id) {
            $.ajax({
                url: "{{ url('/admin-panel/setup-jam-kerja') }}" + "/" + id + "/edit",
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
