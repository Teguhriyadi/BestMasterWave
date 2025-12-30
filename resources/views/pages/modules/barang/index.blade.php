@extends('pages.layouts.app')

@push('title_module', 'Barang')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Barang
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
                        <th class="text-center">SKU Barang</th>
                        <th>Nama Seller</th>
                        <th class="text-center">Harga Modal</th>
                        <th class="text-center">Harga Pembelian Terakhir</th>
                        <th class="text-center">Tanggal Pembelian Terakhir</th>
                        <th class="text-center">Status SKU</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $nomer = 0;
                    @endphp
                    @foreach ($barang as $item)
                        <tr>
                            <td class="text-center">{{ ++$nomer }}.</td>
                            <td class="text-center">{{ $item['sku_barang'] }}</td>
                            <td>{{ $item['seller_id'] }}</td>
                            <td class="text-center">{{ $item['harga_modal'] }}</td>
                            <td class="text-center">{{ $item['harga_pembelian_terakhir'] }}</td>
                            <td class="text-center">{{ $item['tanggal_pembelian_terakhir'] }}</td>
                            <td class="text-center">{{ $item['status_sku'] }}</td>
                            <td class="text-center">
                                <button onclick="editSupplier('{{ $item['id'] }}')" type="button"
                                    class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModalEdit">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <form action="{{ url('/admin-panel/barang/' . $item['id']) }}" method="POST"
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
                <form action="{{ url('/admin-panel/barang') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sku_barang" class="form-label"> SKU Barang </label>
                                    <input type="text" class="form-control" name="sku_barang" id="sku_barang"
                                        placeholder="Masukkan SKU Barang">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="harga_modal" class="form-label"> Harga Modal </label>
                                    <input type="number" min="1" class="form-control" name="harga_modal" id="harga_modal"
                                        placeholder="0">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="harga_pembelian_terakhir" class="form-label"> Harga Pembelian Terakhir </label>
                                    <input type="number" class="form-control" name="harga_pembelian_terakhir" min="1" placeholder="0" id="harga_pembelian_terakhir">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_pembelian_terakhir" class="form-label"> Tanggal Pembelian Terakhir </label>
                                    <input type="datetime-local" class="form-control" name="tanggal_pembelian_terakhir" id="tanggal_pembelian_terakhir">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="seller_id" class="form-label">Nama Seller</label>
                                    <select name="seller_id" class="form-control" id="seller_id">
                                        <option value="">- Pilih -</option>
                                        @foreach ($seller as $item)
                                            <option value="{{ $item['id'] }}">
                                                {{ $item['nama'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status_sku" class="form-label">Status SKU</label>
                                    <select name="status_sku" class="form-control" id="status_sku">
                                        <option value="">- Pilih -</option>
                                        <option value="A">Aktif</option>
                                        <option value="B">Tidak Aktif</option>
                                    </select>
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
                url: "{{ url('/admin-panel/barang') }}" + "/" + id + "/edit",
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
