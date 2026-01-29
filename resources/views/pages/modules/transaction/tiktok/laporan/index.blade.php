@extends('pages.layouts.app')

@push('title_module', 'Tiktok Laporan')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet">
@endpush

@push('content_app')
    <h1 class="h3 mb-4 text-gray-800">Data Tiktok Laporan</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List Data Laporan</h6>
        </div>
        <div class="card-body">
            <form id="filter-form">
                <div class="row">
                    {{-- <div class="col-md-2">
                        <div class="form-group">
                            <label>Nama Seller</label>
                            <select name="nama_seller" class="form-control" id="nama_seller">
                                <option value="">- Pilih -</option>
                                @foreach ($seller as $item)
                                    <option value="{{ $item['nama'] }}">{{ $item['nama'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Filter Kolom Dengan</label>
                            <select name="filter_by" class="form-control" id="filter_by">
                                <option value="">- Pilih -</option>
                                <option value="order_created_time">Order Created Time(UTC)</option>
                                <option selected value="order_settled_time">Order Settled Time(UTC)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Dari</label>
                            <input type="date" class="form-control" name="dari" id="dari" value="{{ now()->subDays(30)->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Sampai</label>
                            <input type="date" class="form-control" name="sampai" id="sampai" value="{{ now()->format('Y-m-d') }}">
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
                <table class="table table-bordered" id="serverSideTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>Order/Adjustment ID</th>
                            <th class="text-center">SKU</th>
                            <th class="text-center">QTY</th>
                            <th class="tect-center">Modal</th>
                            <th class="text-center">Laba/Rugi</th>
                            <th class="text-center">Order Created Time(UTC)</th>
                            <th class="text-center">Order Settled Time(UTC)</th>
                            <th class="text-center">Total Settlement Amount</th>
                            <th class="text-center">Total Revenue</th>
                            <th class="text-center">Fee</th>
                            <th class="text-center">Ongkir</th>
                            <th class="text-center">Ongkir Refund</th>
                            <th class="text-center">Affiliate</th>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            $('#nama_seller').select2({
                theme: 'bootstrap4'
            });

            let table = $('#serverSideTable').DataTable({
                processing: true,
                serverSide: true,
                deferRender: true,
                ajax: {
                    url: "{{ url()->current() }}",
                    data: function(d) {
                        d.nama_seller = $('#nama_seller').val();
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
                        data: 'order_or_adjustment_id',
                        name: 'order_or_adjustment_id'
                    },
                    {
                        data: 'sku_id',
                        name: 'sku_id'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'harga_modal',
                        name: 'harga_modal',
                        className: 'text-center'
                    },
                    {
                        data: 'laba_rugi',
                        name: 'laba_rugi',
                        className: 'text-center'
                    },
                    {
                        data: 'order_created_time',
                        name: 'order_created_time',
                        className: 'text-center'
                    },
                    {
                        data: 'order_settled_time',
                        name: 'order_settled_time',
                        className: 'text-center'
                    },
                    {
                        data: 'total_settlement_amount',
                        name: 'total_settlement_amount',
                        className: 'text-center'
                    },
                    {
                        data: 'total_revenue',
                        name: 'total_revenue',
                        className: 'text-center'
                    },
                    {
                        data: 'total',
                        name: 'total',
                        className: 'text-center'
                    },
                    {
                        data: 'ongkir',
                        name: 'ongkir',
                        className: 'text-center'
                    },
                    {
                        data: 'ongkir_refund',
                        name: 'ongkir_refund',
                        className: 'text-center'
                    },
                    {
                        data: 'affiliate',
                        name: 'affiliate',
                        className: 'text-center'
                    }
                ]
            });

            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                table.draw(); // Refresh tabel dengan filter baru
            });
        });
    </script>
@endpush
