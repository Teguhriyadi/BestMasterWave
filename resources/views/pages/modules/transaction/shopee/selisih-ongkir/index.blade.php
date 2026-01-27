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
                            <input type="date" class="form-control" name="dari" id="dari">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Sampai</label>
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
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>Nama Seller</th>
                            <th>No. Pesanan</th>
                            <th>SKU</th>
                            <th>QTY</th>
                            <th>Nama Kurir</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Ongkir Dibayar Pembeli</th>
                            <th class="text-center">Diskon Ongkir Ditanggung Jasa Kirim</th>
                            <th class="text-center">Gratis Ongkir dari Shopee</th>
                            <th class="text-center">Ongkir yang Diteruskan oleh Shopee ke Jasa Kirim</th>
                            <th class="text-center">Ongkos Kirim Pengembalian Barang</th>
                            <th class="text-center">Kembali ke Biaya Pengiriman Pengirim</th>
                            <th class="text-center">Pengembalian Biaya Kirim</th>
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
                    url: "{{ url('/admin-panel/shopee-selisih-ongkir') }}",
                    data: function(d) {
                        d.nama_seller = $('#nama_seller').val();
                        d.dari = $('#dari').val();
                        d.sampai = $('#sampai').val();
                    }
                },
                columns: [
                    {
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
                        data: 'no_pesanan',
                        name: 'no_pesanan'
                    },
                    {
                        data: 'nomor_referensi_sku',
                        name: 'shopee_pesanan.nomor_referensi_sku'
                    },
                    {
                        data: 'return_qty',
                        name: 'shopee_pesanan.return_qty'
                    },
                    {
                        data: 'nama_kurir',
                        name: 'nama_kurir'
                    },
                    {
                        data: 'total_all',
                        name: 'total_all',
                        className: 'text-center'
                    },
                    {
                        data: 'ongkir_dibayar',
                        name: 'ongkir_dibayar'
                    },
                    {
                        data: 'diskon_ongkir_ditanggung',
                        name: 'diskon_ongkir_ditanggung'
                    },
                    {
                        data: 'gratis_ongkir_shopee',
                        name: 'gratis_ongkir_shopee'
                    },
                    {
                        data: 'ongkir_diteruskan_shopee',
                        name: 'ongkir_diteruskan_shopee'
                    },
                    {
                        data: 'ongkos_kirim_pengembalian',
                        name: 'ongkos_kirim_pengembalian'
                    },
                    {
                        data: 'kembali_ke_biaya_pengiriman',
                        name: 'kembali_ke_biaya_pengiriman'
                    },
                    {
                        data: 'pengembalian_biaya_kirim',
                        name: 'pengembalian_biaya_kirim'
                    }
                ]
            });

            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });
        });
    </script>
@endpush
