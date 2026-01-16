@extends('pages.layouts.app')

@push('title_module', 'Absensi')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Absensi
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
            <a href="{{ url('/admin-panel/absensi/create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Tambah Data
            </a>
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
                        <th>Lokasi</th>
                        <th class="text-center">Tanggal Absensi</th>
                        <th class="text-center">Status Absen</th>
                        <th class="text-center">Tanggal Publish</th>
                        <th class="text-center">Tanggal Modifikasi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $nomer = 0;
                    @endphp
                    @foreach ($log_absensi as $item)
                        <tr>
                            <td class="text-center">{{ ++$nomer }}.</td>
                            @if (empty(Auth::user()->one_divisi_roles))
                                <td>{{ $item['divisi'] }}</td>
                            @endif
                            <td>{{ $item['nama_karyawan'] }}</td>
                            <td>{{ $item['lokasi'] }}</td>
                            <td class="text-center">{{ $item['tanggal_waktu'] }}</td>
                            <td class="text-center">
                                @if ($item["status"] == "Tepat Waktu")
                                    <span class="badge bg-success text-white text-uppercase">
                                        Tepat Waktu
                                    </span>
                                @elseif ($item["status"] == "Terlambat")
                                    <span class="badge bg-danger text-white text-uppercase">
                                        Terlambat
                                    </span>
                                @elseif ($item["status"] == "Pulang")
                                    <span class="badge bg-success text-white text-uppercase">
                                        Pulang
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">{{ $item['upload'] }}</td>
                            <td class="text-center">{{ $item['modif'] }}</td>
                            <td class="text-center">
                                <button onclick="editLogAbsensi('{{ $item['id'] }}')" type="button"
                                    class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModalEdit">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <form action="{{ url('/admin-panel/absensi/' . $item['id']) }}" method="POST"
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

    <!-- Modal Edit -->
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

        function editLogAbsensi(id) {
            $.ajax({
                url: "{{ url('/admin-panel/absensi') }}" + "/" + id + "/edit",
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
