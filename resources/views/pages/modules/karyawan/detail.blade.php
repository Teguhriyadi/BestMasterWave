@extends('pages.layouts.app')

@push('title_module', 'Karyawan')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Detail Data Karyawan
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

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <a href="{{ url('/admin-panel/karyawan') }}" class="btn btn-danger btn-sm">
                        <i class="fa fa-sign-out-alt"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="{{ asset('templating/img/undraw_profile.svg') }}" class="rounded-circle mb-3"
                                width="120" height="120" style="object-fit: cover">
                            <h5 class="font-weight-bold mb-1">
                                {{ $edit->nama }}
                            </h5>
                            <span class="badge badge-primary mb-2">
                                {{ $edit->jabatan->nama_jabatan }}
                            </span>
                            <hr>
                            <p class="mb-1">
                                <i class="fa fa-phone"></i>
                                {{ $edit->no_hp ?? '-' }}
                            </p>
                        </div>
                        <div class="col-md-8">
                            <h5 class="fw-bold">
                                <strong><i class="fa fa-edit"></i> Profil Diri</strong>
                            </h5>
                            <hr>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">ID Sidik Jari</div>
                                <div class="col-sm-8">:
                                    <span class="badge bg-success text-white text-uppercase" style="font-size: 14px">
                                        {{ $edit['id_fp'] }}
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Nomor KTP</div>
                                <div class="col-sm-8">:
                                    @if (empty($edit['no_ktp']))
                                        <span class="badge bg-danger text-white text-uppercase">
                                            Belum Upload
                                        </span>
                                    @else
                                        {{ $edit->no_ktp }}
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Nomor Kartu Keluarga</div>
                                <div class="col-sm-8">:
                                    @if (empty($edit['no_kk']))
                                        <span class="badge bg-danger text-white text-uppercase">
                                            Belum Upload
                                        </span>
                                    @else
                                        {{ $edit->no_kk }}
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Nomor BPJS Kesehatan</div>
                                <div class="col-sm-8">:
                                    @if (empty($edit['no_bpjs_kesehatan']))
                                        <span class="badge bg-danger text-white text-uppercase">
                                            Belum Upload
                                        </span>
                                    @else
                                        {{ $edit->no_bpjs_kesehatan }}
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Nama Panggilan</div>
                                <div class="col-sm-8">: {{ $edit->nama_panggilan }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Tanggal Masuk</div>
                                <div class="col-sm-8">: {{ $edit->tanggal_masuk->locale('id')->translatedFormat('d F Y') }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Nomor Handphone Darurat</div>
                                <div class="col-sm-8">: {{ $edit->no_hp_darurat }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Tempat Lahir</div>
                                <div class="col-sm-8">: {{ $edit->tempat_lahir }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Tanggal Lahir</div>
                                <div class="col-sm-8">: {{ $edit->tanggal_lahir->locale('id')->translatedFormat('d F Y') }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Jenis Kelamin</div>
                                <div class="col-sm-8">: {{ $edit->jenis_kelamin == 'L' ? 'Laki - Laki' : 'Perempuan' }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Alamat</div>
                                <div class="col-sm-8">: {{ $edit->alamat }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Status Pernikahan</div>
                                <div class="col-sm-8">: {{ $edit->status_pernikahan }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Nama Bank</div>
                                <div class="col-sm-8">:
                                    @if (empty($edit['bank']))
                                        <span class="badge bg-danger text-white text-uppercase">
                                            Belum Upload
                                        </span>
                                    @else
                                        {{ $edit->bank }}
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Nomor Rekening</div>
                                <div class="col-sm-8">:
                                    @if (empty($edit['acc_no']))
                                        <span class="badge bg-danger text-white text-uppercase">
                                            Belum Upload
                                        </span>
                                    @else
                                        {{ $edit->acc_no }}
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Nama Rekening</div>
                                <div class="col-sm-8">:
                                    @if (empty($edit['acc_name']))
                                        <span class="badge bg-danger text-white text-uppercase">
                                            Belum Upload
                                        </span>
                                    @else
                                        {{ $edit->acc_name }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h5 class="fw-bold mb-4">
                        <i class="fa fa-edit"></i> Denda Karyawan
                    </h5>

                    <table class="table table-bordered nowrap" id="dataTable-denda" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Tanggal Denda</th>
                                <th class="text-center">Kode Denda</th>
                                <th>Jenis Denda</th>
                                <th>Keterangan</th>
                                <th class="text-center">Nominal Potongan</th>
                                <th>Periode Gaji</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $nomer = 0
                            @endphp
                            @foreach ($denda as $item)
                                <tr>
                                    <td class="text-center">{{ ++$nomer }}.</td>
                                    <td class="text-center">{{ $item["tanggal_denda"] }}</td>
                                    <td class="text-center">{{ $item["kode"] }}</td>
                                    <td>{{ $item["jenis_denda"] }}</td>
                                    <td>{{ $item["keterangan"] }}</td>
                                    <td class="text-center">{{ $item["nominal"] }}</td>
                                    <td>{{ $item["periode_gaji"] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <hr>

                    <h5 class="fw-bold mb-4">
                        <i class="fa fa-book"></i> Peringatan Karyawan
                    </h5>

                    <table class="table table-bordered nowrap" id="dataTable-pelanggaran" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Tanggal Pelanggaran</th>
                                <th class="text-center">Kode Pelanggaran</th>
                                <th>Jenis Pelanggaran</th>
                                <th class="text-center">Tanggal Terbit SP</th>
                                <th class="text-center">Berlaku Sampai</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $nomer = 0
                            @endphp
                            @foreach ($pelanggaran as $item)
                                <tr>
                                    <td class="text-center">{{ ++$nomer }}.</td>
                                    <td class="text-center">{{ $item["tanggal_pelanggaran"] }}</td>
                                    <td class="text-center">{{ $item["kode"] }}</td>
                                    <td>{{ $item["jenis_pelanggaran"] }}</td>
                                    <td class="text-center">{{ $item["tanggal_terbit_sp"] }}</td>
                                    <td class="text-center">{{ $item["berlaku_sampai"] }}</td>
                                    <td>{{ $item["keterangan"] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endpush

@push("js_style")
    <script src="{{ asset('templating/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#dataTable-denda').DataTable({
                scrollX: true,
                autoWidth: false,
                responsive: false
            });

            $('#dataTable-pelanggaran').DataTable({
                scrollX: true,
                autoWidth: false,
                responsive: false
            });
        });
    </script>
@endpush
