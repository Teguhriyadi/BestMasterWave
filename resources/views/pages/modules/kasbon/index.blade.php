@extends('pages.layouts.app')

@push('title_module', 'Kasbon')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Kasbon
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
        {{-- @if (canPermission('kasbon.create')) --}}
        <div class="card-header py-3">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                <i class="fa fa-plus"></i> Tambah Data
            </button>
        </div>
        {{-- @endif --}}
        <div class="card-body">
            <table class="table table-bordered nowrap" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th class="text-center">Jumlah Awal</th>
                        <th class="text-center">Tanggal Mulai</th>
                        <th>Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $nomer = 0;
                    @endphp
                    @foreach ($kasbon as $item)
                        <tr>
                            <td class="text-center">{{ ++$nomer }}.</td>
                            <td>{{ $item['karyawan']['nama'] }}</td>
                            <td>{{ $item['karyawan']['jabatan'] }}</td>
                            <td class="text-center">{{ $item['jumlah_awal'] }}</td>
                            <td class="text-center">{{ $item['tanggal_mulai'] }}</td>
                            <td>{{ $item['keterangan'] }}</td>
                            <td class="text-center">
                                <a href="{{ url('/admin-panel/kasbon/' . $item['id'] . '/show') }}" class="btn btn-info btn-sm">
                                    <i class="fa fa-search"></i> Detail
                                </a>
                                {{-- @if (canPermission('bank.edit')) --}}
                                <button onclick="editSupplier('{{ $item['id'] }}')" type="button"
                                    class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModalEdit">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                {{-- @endif --}}
                                {{-- @if (canPermission('bank.delete')) --}}
                                <form action="{{ url('/admin-panel/kasbon/' . $item['id']) }}" method="POST"
                                    style="display: inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Yakin ? Ingin Menghapus Data Ini?')" type="submit"
                                        class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </form>
                                {{-- @endif --}}
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
                <form action="{{ url('/admin-panel/kasbon') }}" method="POST">
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
                                    <label for="jumlah" class="form-label">
                                        Jumlah
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="number" class="form-control @error('jumlah') is-invalid @enderror"
                                        name="jumlah" id="jumlah" placeholder="0" min="1"
                                        value="{{ old('jumlah') }}">
                                    @error('jumlah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_mulai" class="form-label">
                                        Tanggal Mulai
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                        name="tanggal_mulai" id="tanggal_mulai" placeholder="0" min="1"
                                        value="{{ old('tanggal_mulai') }}">
                                    @error('tanggal_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="jumlah" class="form-label">
                                Keterangan
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

        function editSupplier(id) {
            $.ajax({
                url: "{{ url('/admin-panel/kasbon') }}" + "/" + id + "/edit",
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
