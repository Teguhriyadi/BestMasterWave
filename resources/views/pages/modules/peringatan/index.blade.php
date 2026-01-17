@extends('pages.layouts.app')

@push('title_module', 'Peringatan')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Peringatan
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
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                <i class="fa fa-plus"></i> Tambah Data
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered nowrap" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th>Nama Karyawan</th>
                        <th>Jenis Peringatan</th>
                        <th class="text-center">Tanggal Pelanggaran</th>
                        <th class="text-center">Tanggal Terbit SP</th>
                        <th>Berlaku Sampai</th>
                        <th>Keterangan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $nomer = 0;
                    @endphp
                    @foreach ($peringatan as $item)
                        <tr>
                            <td class="text-center">{{ ++$nomer }}.</td>
                            <td>{{ $item['karyawan'] }}</td>
                            <td>{{ $item['jenis_peringatan'] }}</td>
                            <td class="text-center">{{ $item['tanggal_pelanggaran'] }}</td>
                            <td>{{ $item['tanggal_terbit_sp'] }}</td>
                            <td>{{ $item['berlaku_sampai'] }}</td>
                            <td>{{ $item['keterangan'] }}</td>
                            <td class="text-center">{!! $item['status'] !!}</td>
                            <td class="text-center">
                                <button onclick="ubahStatus('{{ $item['id'] }}')" type="button"
                                    class="btn btn-info btn-sm" data-toggle="modal" data-target="#exampleModalUbahStatus">
                                    <i class="fa fa-edit"></i> Ubah Status
                                </button>
                                <button onclick="editPeringatan('{{ $item['id'] }}')" type="button"
                                    class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModalEdit">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <form action="{{ url('/admin-panel/peringatan/' . $item['id']) }}" method="POST"
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
                <form action="{{ url('/admin-panel/peringatan') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="karyawan_id">
                                        Nama Karyawan
                                        <small class="text-danger">*</small>
                                    </label>
                                    <select name="karyawan_id"
                                        class="form-control @error('karyawan_id') is-invalid @enderror" id="karyawan_id">
                                        <option value="">- Pilih -</option>
                                        @foreach ($karyawan as $item)
                                            <option value="{{ $item['id'] }}"
                                                {{ old('karyawan_id') == $item['id'] ? 'selected' : '' }}>
                                                {{ $item['nama'] }} - {{ $item['jabatan'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('karyawan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jenis_peringatan_id">
                                        Jenis Peringatan
                                        <small class="text-danger">*</small>
                                    </label>
                                    <select name="jenis_peringatan_id"
                                        class="form-control @error('jenis_peringatan_id') is-invalid @enderror"
                                        id="jenis_peringatan_id">
                                        <option value="">- Pilih -</option>
                                        @foreach ($jenis_peringatan as $item)
                                            <option value="{{ $item['id'] }}"
                                                {{ old('jenis_peringatan_id') == $item['id'] ? 'selected' : '' }}>
                                                {{ $item['kode'] }} - {{ $item['nama_peringatan'] }} - {{ $item['level'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jenis_peringatan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tanggal_pelanggaran" class="form-label">
                                        Tanggal Pelanggaran
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="date" class="form-control @error('tanggal_pelanggaran') is-invalid @enderror"
                                        name="tanggal_pelanggaran" id="tanggal_pelanggaran"
                                        value="{{ old('tanggal_pelanggaran') }}">
                                    @error('tanggal_pelanggaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tanggal_terbit_sp" class="form-label">
                                        Tanggal Terbit SP
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="date"
                                        class="form-control @error('tanggal_terbit_sp') is-invalid @enderror"
                                        name="tanggal_terbit_sp" id="tanggal_terbit_sp" value="{{ old('tanggal_terbit_sp') }}">
                                    @error('tanggal_terbit_sp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="berlaku_sampai" class="form-label">
                                        Berlaku Sampai
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="date" class="form-control @error('berlaku_sampai') is-invalid @enderror" name="berlaku_sampai" id="berlaku_sampai" value="{{ old('berlaku_sampai') }}">
                                    @error('berlaku_sampai')
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

    <!-- Modal Ubah Status -->
    <div class="modal fade" id="exampleModalUbahStatus" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="exampleModalLabel">
                        <i class="fa fa-edit"></i> Ubah Status Data
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modal-content-ubah-status">

                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Ubah Status -->
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

        function editPeringatan(id) {
            $.ajax({
                url: "{{ url('/admin-panel/peringatan') }}" + "/" + id + "/edit",
                type: "GET",
                success: function(response) {
                    $("#modal-content-edit").html(response)
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function ubahStatus(id) {
            $.ajax({
                url: "{{ url('/admin-panel/peringatan') }}" + "/" + id + "/ubah-status",
                type: "GET",
                success: function(response) {
                    $("#modal-content-ubah-status").html(response)
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    </script>
@endpush
