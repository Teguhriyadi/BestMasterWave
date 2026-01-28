@extends('pages.layouts.app')

@push('title_module', 'Selisih Ongkir')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Selisih Ongkir
    </h1>

    @if (session('success'))
        <div class="alert alert-success">
            <strong>Berhasil,</strong> {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            <strong>Gagal</strong>,{{ session('error') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <form id="filter-form">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Nama Seller</label>
                            <select name="nama_seller" class="form-control" id="nama_seller">
                                <option value="">- Pilih -</option>
                                @foreach ($seller as $item)
                                    <option value="{{ $item['nama'] }}">{{ $item['nama'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Dari</label>
                            <input type="date" class="form-control" name="dari" id="dari" value="{{ now()->format('Y-m-d') }}">
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
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="button" id="btn-download" class="btn btn-success btn-sm w-100">
                                <i class="fa fa-download"></i> Download Data
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <hr>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>Nama Seller</th>
                            <th>Order ID</th>
                            <th>SKU</th>
                            <th>QTY</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Shipping Cost Passed</th>
                            <th class="text-center">Replacement Shipping Fee</th>
                            <th class="text-center">Exchange Shipping Fee</th>
                            <th class="text-center">Shipping Cost Borne</th>
                            <th class="text-center">Shipping Cost Paid</th>
                            <th class="text-center">Refunded Shipping Cost</th>
                            <th class="text-center">Return Shipping Costs</th>
                            <th class="text-center">Shipping Cost Subsidy</th>
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

    <script>
        $(document).ready(function() {
            $('#nama_seller').select2({
                theme: 'bootstrap4'
            });

            let table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                pageLength: 25,
                ajax: {
                    url: "{{ url('/admin-panel/tiktok-selisih-ongkir') }}",
                    data: function(d) {
                        d.nama_seller = $('#nama_seller').val();
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
                        data: 'nama_seller',
                        name: 'nama_seller'
                    },
                    {
                        data: 'order_or_adjustment_id',
                        name: 'order_or_adjustment_id'
                    },
                    {
                        data: 'sku_id',
                        name: 'tiktok_pesanan.sku_id'
                    },
                    {
                        data: 'quantity',
                        name: 'tiktok_pesanan.quantity'
                    },
                    {
                        data: 'total_all',
                        name: 'total_all',
                        className: 'text-center'
                    },
                    {
                        data: 'shipping_costs_passed',
                        name: 'shipping_costs_passed'
                    },
                    {
                        data: 'replacement_shipping_fee',
                        name: 'replacement_shipping_fee'
                    },
                    {
                        data: 'exchange_shipping_fee',
                        name: 'exchange_shipping_fee'
                    },
                    {
                        data: 'shipping_cost_borne',
                        name: 'shipping_cost_borne'
                    },
                    {
                        data: 'shipping_cost_paid',
                        name: 'shipping_cost_paid'
                    },
                    {
                        data: 'refunded_shipping_cost_paid',
                        name: 'refunded_shipping_cost_paid'
                    },
                    {
                        data: 'return_shipping_costs',
                        name: 'return_shipping_costs'
                    },
                    {
                        data: 'shipping_cost_subsidy',
                        name: 'shipping_cost_subsidy'
                    },
                ]
            });

            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });
        });

        $('#btn-download').on('click', function() {

            let params = $.param({
                nama_seller: $('#nama_seller').val(),
                dari: $('#dari').val(),
                sampai: $('#sampai').val()
            });

            window.location.href =
                "{{ url('/admin-panel/tiktok-selisih-ongkir/download') }}?" + params;
        });
    </script>
@endpush
