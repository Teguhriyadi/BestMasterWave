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
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
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
                        <th class="text-center">No. NPWP</th>
                        <th>Nama Supplier</th>
                        <th>Kontak Yang Bisa Dihubungi</th>
                        <th>Tempo Pembayaran</th>
                        <th>No. Rekening</th>
                        <th>Nama Rekening</th>
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
                            <td class="text-center">{{ $item['no_npwp'] }}</td>
                            <td>{{ $item['nama_supplier'] }}</td>
                            <td>
                                <span class="badge bg-success text-white">
                                    @if ($item['kontak_hubungi'] == 'WA_HP')
                                        WhatsApp & Nomor Handphone
                                    @endif
                                </span>
                            </td>
                            <td>{{ $item['tempo_pembayaran'] }} Hari</td>
                            <td>{{ $item['no_rekening'] }}</td>
                            <td>{{ $item['nama_rekening'] }}</td>
                            <td>{{ $item['bank'] }}</td>
                            <td>{{ $item['alamat'] }}</td>
                            <td class="text-center">
                                <button onclick="editSupplier('{{ $item['id'] }}')" type="button"
                                    class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModalEdit">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <form action="{{ url('/admin-panel/supplier/' . $item['id']) }}" method="POST"
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
                <form action="{{ url('/admin-panel/supplier') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_supplier" class="form-label"> Nama Supplier </label>
                                    <input type="text" class="form-control" name="nama_supplier" id="nama_supplier"
                                        placeholder="Masukkan Nama Supplier">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="no_npwp" class="form-label"> No. NPWP </label>
                                    <input type="text" class="form-control" name="no_npwp" id="no_npwp"
                                        placeholder="Masukkan No. NPWP">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kontak_hubungi" class="form-label"> Jenis Kontak Yang Dihubungi </label>
                                    <select name="kontak_hubungi" class="form-control" id="kontak_hubungi">
                                        <option value="">- Pilih -</option>
                                        <option value="WA_HP">WhatsApp + Nomor Handphone</option>
                                        <option value="SMS">SMS</option>
                                        <option value="WA">WhatApp</option>
                                        <option value="NO_HP">Nomor Handphone</option>
                                        <option value="GMAIL">Email</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_kontak" class="form-label"> Kontak Yang Bisa Dihubungi </label>
                                    <input type="text" class="form-control" name="nomor_kontak" id="nomor_kontak"
                                        placeholder="Contoh : 081214711741 / ex@gmail.com">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="no_rekening" class="form-label">No. Rekening</label>
                                    <input type="text" class="form-control" name="no_rekening" id="no_rekening"
                                        placeholder="Masukkan No. Rekening">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nama_rekening" class="form-label">Nama Rekening</label>
                                    <input type="text" class="form-control" name="nama_rekening" id="nama_rekening"
                                        placeholder="Masukkan Nama Rekening">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bank_id" class="form-label">Nama Bank</label>
                                    <select name="bank_id" class="form-control" id="bank_id">
                                        <option value="">- Pilih -</option>
                                        @foreach ($bank as $item)
                                            <option value="{{ $item['id'] }}">
                                                {{ $item['alias'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ketentuan_tempo_pembayaran" class="form-label">Ketentuan Tempo
                                        Pembayaran</label>
                                    <input type="text" class="form-control" name="ketentuan_tempo_pembayaran"
                                        id="ketentuan_tempo_pembayaran" placeholder="Masukkan Tempo Pembayaran">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rate_ppn" class="form-label">Rate PPN</label>
                                    <input type="number" class="form-control" name="rate_ppn" id="rate_ppn"
                                        placeholder="0" min="1">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pkp" class="form-label">PKP</label>
                            <input type="text" class="form-control" name="pkp" id="pkp"
                                placeholder="Masukkan Data PKP">
                        </div>
                        <div class="form-group">
                            <label for="alamat" class="form-label"> Alamat </label>
                            <textarea name="alamat" class="form-control" id="alamat" rows="5" placeholder="Masukkan Alamat"></textarea>
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
