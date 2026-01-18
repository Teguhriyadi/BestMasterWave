@extends('pages.layouts.app')

@push('title_module', 'Denda')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Denda
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
            @if (canPermission('denda.create'))
                <a href="{{ url('/admin-panel/denda/create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Tambah Data
                </a>
            @endif
        </div>
        <div class="card-body">
            <table class="table table-bordered nowrap" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th>Karyawan</th>
                        <th>Jabatan</th>
                        <th>Tanggal Denda</th>
                        <th>Jenis Denda</th>
                        <th>Periode Potongan Gaji</th>
                        <th class="text-center">Status</th>
                        <th>Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $nomer = 0;
                    @endphp
                    @foreach ($denda as $item)
                        <tr>
                            <td class="text-center">{{ ++$nomer }}.</td>
                            <td>{{ $item['karyawan'] }}</td>
                            <td>{{ $item['jabatan'] }}</td>
                            <td>{{ $item['tanggal'] }}</td>
                            <td>{{ $item['kode'] }} - {{ $item['jenis'] }}</td>
                            <td>{{ $item['periode_gaji'] }}</td>
                            <td class="text-center">{!! $item['status'] !!}</td>
                            <td>{{ $item['keterangan'] }}</td>
                            <td class="text-center">
                                @if (canPermission('denda.change_status'))
                                    <button onclick="ubahStatus('{{ $item['id'] }}')" type="button"
                                        class="btn btn-info btn-sm" data-toggle="modal"
                                        data-target="#exampleModalUbahStatus">
                                        <i class="fa fa-edit"></i> Ubah Status
                                    </button>
                                @endif
                                @if (canPermission('denda.edit'))
                                    <button onclick="editDenda('{{ $item['id'] }}')" type="button"
                                        class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModalEdit">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                @endif
                                @if (canPermission('denda.delete'))
                                    <form action="{{ url('/admin-panel/denda/' . $item['id']) }}" method="POST"
                                        style="display: inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Yakin ? Ingin Menghapus Data Ini?')" type="submit"
                                            class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                @endif

                                @if (!canPermission('denda.edit') && !canPermission('denda.delete') && !canPermission('denda.change_status'))
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

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

        function editDenda(id) {
            $.ajax({
                url: "{{ url('/admin-panel/denda') }}" + "/" + id + "/edit",
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
                url: "{{ url('/admin-panel/denda') }}" + "/" + id + "/ubah-status",
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
