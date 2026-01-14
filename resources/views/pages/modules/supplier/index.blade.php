@extends('pages.layouts.app')

@push('title_module', 'Supplier')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Supplier
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
        @if (canPermission('supplier.create'))
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
                        @if (empty(Auth::user()->one_divisi_roles))
                            <th>Divisi</th>
                        @endif
                        <th class="text-center">No. NPWP</th>
                        <th>Nama Supplier</th>
                        <th>Jenis Kontak Terhubung</th>
                        <th>Kontak Yang Bisa Dihubungi</th>
                        <th>Tempo Pembayaran</th>
                        <th>No. Rekening</th>
                        <th>Nama Rekening</th>
                        <th class="text-center">PKP</th>
                        <th>Bank</th>
                        <th>Alamat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $nomer = 0;
                    @endphp
                    @foreach ($supplier as $item)
                        <tr>
                            <td class="text-center">{{ ++$nomer }}.</td>
                            @if (empty(Auth::user()->one_divisi_roles))
                                <td>{{ $item['divisi'] }}</td>
                            @endif
                            <td class="text-center">{{ $item['no_npwp'] }}</td>
                            <td>{{ $item['nama_supplier'] }}</td>
                            <td>{{ $item['kontak_hubungi'] }}</td>
                            <td>{{ $item['nomor_kontak'] }}</td>
                            <td>{{ $item['tempo_pembayaran'] }} Hari</td>
                            <td>{{ $item['no_rekening'] }}</td>
                            <td>{{ $item['nama_rekening'] }}</td>
                            <td class="text-center">{{ $item['status_pkp'] }}</td>
                            <td>{{ $item['bank'] }}</td>
                            <td>{{ $item['alamat'] }}</td>
                            <td class="text-center">
                                @if (canPermission('supplier.edit'))
                                    <button onclick="editSupplier('{{ $item['id'] }}')" type="button"
                                        class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModalEdit">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                @endif
                                @if (canPermission('supplier.delete'))
                                    <form action="{{ url('/admin-panel/supplier/' . $item['id']) }}" method="POST"
                                        style="display: inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Yakin ? Ingin Menghapus Data Ini?')" type="submit"
                                            class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                @endif

                                @if (!canPermission('supplier.edit') && !canPermission('supplier.delete'))
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
                <form action="{{ url('/admin-panel/supplier') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_supplier" class="form-label">
                                        Nama Supplier
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="text" class="form-control @error('nama_supplier') is-invalid @enderror"
                                        name="nama_supplier" id="nama_supplier" placeholder="Masukkan Nama Supplier"
                                        value="{{ old('nama_supplier') }}">
                                    @error('nama_supplier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="no_npwp" class="form-label">
                                        No. NPWP
                                    </label>
                                    <input type="text" class="form-control @error('no_npwp') is-invalid @enderror"
                                        name="no_npwp" id="no_npwp" placeholder="Masukkan No. NPWP"
                                        value="{{ old('no_npwp') }}">
                                    @error('no_npwp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kontak_hubungi" class="form-label">
                                        Kontak Person
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="text" class="form-control @error('kontak_hubungi') is-invalid @enderror"
                                        name="kontak_hubungi" id="kontak_hubungi" placeholder="Masukkan Kontak Person"
                                        value="{{ old('kontak_hubungi') }}">
                                    @error('kontak_hubungi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_kontak" class="form-label">
                                        Kontak Yang Bisa Dihubungi
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="text" class="form-control @error('nomor_kontak') is-invalid @enderror"
                                        name="nomor_kontak" id="nomor_kontak"
                                        placeholder="Contoh : 081214711741 / ex@gmail.com"
                                        value="{{ old('nomor_kontak') }}">
                                    @error('nomor_kontak')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="no_rekening" class="form-label">
                                        No. Rekening
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="text" class="form-control @error('no_rekening') is-invalid @enderror"
                                        name="no_rekening" id="no_rekening" placeholder="Masukkan No. Rekening"
                                        value="{{ old('no_rekening') }}">
                                    @error('no_rekening')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nama_rekening" class="form-label">
                                        Nama Rekening
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="text"
                                        class="form-control @error('nama_rekening') is-invalid @enderror"
                                        name="nama_rekening" id="nama_rekening" placeholder="Masukkan Nama Rekening"
                                        value="{{ old('nama_rekening') }}">
                                    @error('nama_rekening')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bank_id" class="form-label">
                                        Nama Bank
                                        <small class="text-danger">*</small>
                                    </label>
                                    <select name="bank_id" class="form-control @error('bank_id') is-invalid @enderror"
                                        id="bank_id">
                                        <option value="">- Pilih -</option>
                                        @foreach ($bank as $item)
                                            <option value="{{ $item['id'] }}"
                                                {{ old('bank_id') == $item['id'] ? 'selected' : '' }}>
                                                {{ $item['alias'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bank_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ketentuan_tempo_pembayaran" class="form-label">
                                        Ketentuan Tempo Pembayaran
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="text"
                                        class="form-control @error('ketentuan_tempo_pembayaran') is-invalid @enderror"
                                        name="ketentuan_tempo_pembayaran" id="ketentuan_tempo_pembayaran"
                                        placeholder="Masukkan Tempo Pembayaran"
                                        value="{{ old('ketentuan_tempo_pembayaran') }}">
                                    @error('ketentuan_tempo_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rate_ppn" class="form-label">
                                        Rate PPN
                                    </label>
                                    <input type="number" class="form-control @error('rate_ppn') is-invalid @enderror"
                                        name="rate_ppn" id="rate_ppn" placeholder="0" min="0" max="100"
                                        value{{ old('rate_ppn') }}>
                                    @error('rate_ppn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pkp" class="form-label">
                                PKP
                                <small class="text-danger">*</small>
                            </label>
                            <select name="pkp" class="form-control @error('pkp') is-invalid @enderror"
                                id="pkp">
                                <option value="">- Pilih -</option>
                                <option {{ old('pkp') == 'PKP' ? 'selected' : '' }} value="PKP">PKP</option>
                                <option {{ old('pkp') == 'Non PKP' ? 'selected' : '' }} value="Non PKP">Non PKP</option>
                            </select>
                            @error('pkp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="alamat" class="form-label">
                                Alamat
                                <small class="text-danger">*</small>
                            </label>
                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" id="alamat" rows="5"
                                placeholder="Masukkan Alamat">{{ old('alamat') }}</textarea>
                            @error('alamat')
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

        function editSupplier(id) {
            $.ajax({
                url: "{{ url('/admin-panel/supplier') }}" + "/" + id + "/edit",
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
