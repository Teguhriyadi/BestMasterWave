@extends('pages.layouts.app')

@push('title_module', 'Selisih Ongkir')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
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
                    <tbody>
                        @php
                            $nomer = 1;
                        @endphp
                        @foreach ($tiktok_pendapatan as $item)
                            <tr>
                                <td class="text-center">{{ $nomer++ }}.</td>
                                <td>{{ $item->nama_seller }}</td>
                                <td>{{ $item->order_or_adjustment_id }}</td>
                                <td>{{ $item->sku_id }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-center">{{ $item->total_all }}</td>
                                <td class="text-center">{{ $item->shipping_costs_passed }}</td>
                                <td class="text-center">{{ $item->replacement_shipping_fee }}</td>
                                <td class="text-center">{{ $item->exchange_shipping_fee }}</td>
                                <td class="text-center">{{ $item->shipping_cost_borne }}</td>
                                <td class="text-center">{{ $item->shipping_cost_paid }}</td>
                                <td class="text-center">{{ $item->refunded_shipping_cost_paid }}</td>
                                <td class="text-center">{{ $item->return_shipping_costs }}</td>
                                <td class="text-center">{{ $item->shipping_cost_subsidy }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endpush

@push('js_style')
    <script src="{{ asset('templating/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                scrollX: true,
                scrollCollapse: true,
                autoWidth: false,
                pageLength: 25,
                ordering: true
            });
        });
    </script>
@endpush
