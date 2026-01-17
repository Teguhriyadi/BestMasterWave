@extends('pages.layouts.app')

@push('title_module', 'Karyawan')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Karyawan
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
        @if (canPermission('karyawan.create'))
            <div class="card-header py-3">
                <a href="{{ url('/admin-panel/karyawan/create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Tambah Data
                </a>
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
                        <th>ID Sidik Jari</th>
                        <th>No. KTP</th>
                        <th>Nama Karyawan</th>
                        <th class="text-center">Tanggal Masuk</th>
                        <th>No. KK</th>
                        <th>No. Rekening</th>
                        <th>No. BPJS Kesehatan</th>
                        <th>No. Handphone</th>
                        <th>No. Handphone Darurat</th>
                        <th>Jenis Kelamin</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $nomer = 0;
                    @endphp
                    @foreach ($karyawan as $item)
                        <tr>
                            <td class="text-center">{{ ++$nomer }}.</td>
                            @if (empty(Auth::user()->one_divisi_roles))
                                <td>{{ $item['divisi'] }}</td>
                            @endif
                            <td>{{ $item['sidik_jari'] }}</td>
                            <td>{!! $item['no_ktp'] !!}</td>
                            <td>{{ $item['nama'] }}</td>
                            <td class="text-center">{{ $item['tanggal_masuk'] }}</td>
                            <td>{!! $item['no_kk'] !!}</td>
                            <td>{!! $item['acc_no'] !!}</td>
                            <td>{!! $item['no_bpjs_kesehatan'] !!}</td>
                            <td>{{ $item['no_hp'] }}</td>
                            <td>{{ $item['no_hp_darurat'] }}</td>
                            <td class="text-center">{{ $item['jenis_kelamin'] }}</td>
                            <td class="text-center">
                                @if (canPermission('karyawan.show'))
                                    <button onclick="lihatLog('{{ $item['id'] }}')" type="button"
                                        class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#exampleModalLihatLog">
                                        <i class="fa fa-search"></i> Lihat Log
                                    </button>
                                    <a href="{{ url('/admin-panel/karyawan/' . $item['id'] . '/show') }}"
                                        class="btn btn-info btn-sm">
                                        <i class="fa fa-search"></i> Detail
                                    </a>
                                @endif
                                @if (canPermission('karyawan.edit'))
                                    <a href="{{ url('/admin-panel/karyawan/' . $item['id'] . '/edit') }}"
                                        class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                @endif
                                @if (canPermission('karyawan.delete'))
                                    <form action="{{ url('/admin-panel/karyawan/' . $item['id']) }}" method="POST"
                                        style="display: inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Yakin ? Ingin Menghapus Data Ini?')" type="submit"
                                            class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                @endif

                                @if (!canPermission('karyawan.edit') && !canPermission('karyawan.delete') && !canPermission('karyawan.show'))
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Lihat Log -->
    <div class="modal fade" id="exampleModalLihatLog" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="exampleModalLabel">
                        <i class="fa fa-search"></i> Log Data
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

        function lihatLog(id) {
            $.ajax({
                url: "{{ url('/admin-panel/karyawan') }}" + "/" + id + "/lihat-log",
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
