@extends('pages.layouts.app')

@push('title_module', 'List Data Shopee Pesanan')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('content_app')
    <h1 class="h3 mb-4 text-gray-800">Data Pesanan Shopee</h1>

    <a href="{{ url('/admin-panel/shopee/pesanan') }}" class="btn btn-danger btn-sm mb-4">
        <i class="fa fa-sign-out-alt"></i> Kembali
    </a>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List Data Pesanan</h6>
        </div>
        <div class="card-body">
            <form id="filter-form">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_by">Filter Kolom Dengan</label>
                            <select name="filter_by" class="form-control" id="filter_by">
                                <option value="">- Pilih -</option>
                                <option value="waktu_pesanan_dibuat">Waktu Pesanan Dibuat</option>
                                <option value="waktu_pembayaran_dilakukan">Waktu Pembayaran Dilakukan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="dari">Dari</label>
                            <input type="date" class="form-control" name="dari" id="dari">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sampai">Sampai</label>
                            <input type="date" class="form-control" name="sampai" id="sampai">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="fa fa-search"></i> Filter Data
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <hr>
            <div class="table-responsive">
                <table class="table table-bordered" id="pesananTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>No. Pesanan</th>
                            <th>No. Referensi SKU</th>
                            <th>Nama Produk</th>
                            <th class="text-center">Waktu Dibuat</th>
                            <th class="text-center">Waktu Bayar</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endpush

@push('js_style')
    <script src="{{ asset('templating/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            let table = $('#pesananTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url()->current() }}",
                    data: function(d) {
                        d.filter_by = $('#filter_by').val();
                        d.dari = $('#dari').val();
                        d.sampai = $('#sampai').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'no_pesanan',
                        name: 'no_pesanan'
                    },
                    {
                        data: 'nomor_referensi_sku',
                        name: 'nomor_referensi_sku'
                    },
                    {
                        data: 'nama_produk',
                        name: 'nama_produk'
                    },
                    {
                        data: 'waktu_pesanan_dibuat',
                        name: 'waktu_pesanan_dibuat',
                        className: 'text-center'
                    },
                    {
                        data: 'waktu_pembayaran_dilakukan',
                        name: 'waktu_pembayaran_dilakukan',
                        className: 'text-center'
                    },
                    {
                        data: 'status_pesanan',
                        name: 'status_pesanan',
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ]
            });

            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                table.draw();
            });
        });
    </script>
@endpush
