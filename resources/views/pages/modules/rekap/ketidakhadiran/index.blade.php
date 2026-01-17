@extends('pages.layouts.app')

@push('title_module', 'Ketidakhadiran')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Ketidakhadiran
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
                        @if (empty(Auth::user()->one_divisi_roles))
                            <th>Divisi</th>
                        @endif
                        <th>Nama Karyawan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Tanggal Absen</th>
                        <th>Alasan</th>
                        <th class="text-center">Tanggal Diinputkan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $nomer = 0;
                    @endphp
                    @foreach ($absensi as $item)
                        <tr>
                            <td class="text-center">{{ ++$nomer }}.</td>
                            @if (empty(Auth::user()->one_divisi_roles))
                                <td>{{ $item['divisi'] }}</td>
                            @endif
                            <td>{{ $item['nama'] }}</td>
                            <td class="text-center">{{ $item['status'] }}</td>
                            <td class="text-center">{{ $item['tanggal'] }}</td>
                            <td>{{ $item['alasan'] }}</td>
                            <td class="text-center">{{ $item['upload'] }}</td>
                            <td class="text-center">
                                <button onclick="editAbsensi('{{ $item['id'] }}')" type="button"
                                    class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModalEdit">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <form action="{{ url('/admin-panel/ketidakhadiran/' . $item['id']) }}" method="POST"
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
                <form action="{{ url('/admin-panel/ketidakhadiran') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="karyawan_id" class="form-label">
                                Nama Karyawan
                                <small class="text-danger">*</small>
                            </label>
                            <select name="karyawan_id" class="form-control @error('karyawan_id') is-invalid @enderror"
                                id="karyawan_id">
                                <option value="">- Pilih -</option>
                                @foreach ($karyawan as $item)
                                    <option value="{{ $item['id'] }}">
                                        {{ $item['nama'] }} - {{ $item['jabatan'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('karyawan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label">
                                        Status
                                        <small class="text-danger">*</small>
                                    </label>
                                    <select name="status" class="form-control @error('status') is-invalid @enderror"
                                        id="status">
                                        <option value="">- Pilih -</option>
                                        <option value="Alfa">Alfa</option>
                                        <option value="Sakit">Sakit</option>
                                        <option value="Izin">Izin</option>
                                        <option value="Cuti">Cuti</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal" class="form-label">
                                        Tanggal
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                        name="tanggal" id="tanggal" value="{{ old('tanggal') }}">
                                    @error('tanggal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="alasan" class="form-label">
                                Alasan
                            </label>
                            <textarea name="alasan" class="form-control @error('alasan') is-invalid @enderror" id="alasan" rows="5"
                                placeholder="Masukkan Alasan">{{ old('alasan') }}</textarea>
                            @error('alasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="foto" class="form-label">
                                Foto
                            </label>
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" name="foto"
                                id="foto">
                            @error('foto')
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

        function editAbsensi(id) {
            $.ajax({
                url: "{{ url('/admin-panel/ketidakhadiran') }}" + "/" + id + "/edit",
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
