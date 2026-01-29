@extends('pages.layouts.app')

@push('title_module', 'Shopee Laporan')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet">
@endpush

@push('content_app')
    <h1 class="h3 mb-4 text-gray-800">Data Laporan Shopee</h1>

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
                                    <option value="{{ $item["nama"] }}">{{ $item["nama"] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Filter Kolom Dengan</label>
                            <select name="filter_by" class="form-control" id="filter_by">
                                <option value="">- Pilih -</option>
                                <option value="waktu_pesanan">Waktu Pesanan</option>
                                <option selected value="tanggal_dana_dilepaskan">Tanggal Dana Dilepaskan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Dari</label>
                            <input type="date" class="form-control" name="dari" id="dari"
                                value="{{ now()->subDays(30)->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Sampai</label>
                            <input type="date" class="form-control" name="sampai" id="sampai"
                                value="{{ now()->format('Y-m-d') }}">
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

            <table class="table table-bordered" id="serverSideTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th>No. Pesanan</th>
                        <th>Nomor SKU</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center">Harga Modal</th>
                        <th class="text-center">Laba/Rugi</th>
                        <th>Waktu Pesanan Dibuat</th>
                        <th class="text-center">Metode Pembayaran</th>
                        <th class="text-center">Tanggal Dana Dilepaskan</th>
                        <th class="text-center">Harga Asli Produk</th>
                        <th class="text-center">Total Diskon Produk</th>
                        <th class="text-center">Jumlah Pengembalian Dana</th>
                        <th class="text-center">Diskon</th>
                        <th class="text-center">Ongkir</th>
                        <th class="text-center">Biaya</th>
                        <th class="text-center">Total Penghasilan</th>
                        <th class="text-center">Kompensasi</th>
                        <th class="text-center">Jasa Kirim</th>
                        <th class="text-center">Nama Kurir</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
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
                scrollX: true,
                scrollCollapse: true,
                autoWidth: false,
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
                        data: 'no_pesanan',
                        name: 'no_pesanan'
                    },
                    {
                        data: 'nomor_referensi_sku',
                        name: 'nomor_referensi_sku'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'harga_modal',
                        name: 'harga_modal'
                    },
                    {
                        data: 'laba_rugi',
                        name: 'laba_rugi'
                    },
                    {
                        data: 'waktu_pesanan',
                        name: 'waktu_pesanan',
                        className: 'text-center'
                    },
                    {
                        data: 'metode_pembayaran',
                        name: 'metode_pembayaran',
                        className: 'text-center'
                    },
                    {
                        data: 'tanggal_dana_dilepaskan',
                        name: 'tanggal_dana_dilepaskan',
                        className: 'text-center'
                    },
                    {
                        data: 'harga_asli',
                        name: 'harga_asli',
                        className: 'text-center'
                    },
                    {
                        data: 'total_diskon',
                        name: 'total_diskon',
                        className: 'text-center'
                    },
                    {
                        data: 'jumlah_pengembalian',
                        name: 'jumlah_pengembalian',
                        className: 'text-center'
                    },
                    {
                        data: 'diskon',
                        name: 'diskon',
                        className: 'text-center'
                    },
                    {
                        data: 'ongkir',
                        name: 'ongkir',
                        className: 'text-center'
                    },
                    {
                        data: 'biaya',
                        name: 'biaya',
                        className: 'text-center'
                    },
                    {
                        data: 'total_penghasilan',
                        name: 'total_penghasilan',
                        className: 'text-center'
                    },
                    {
                        data: 'kompensasi',
                        name: 'kompensasi',
                        className: 'text-center'
                    },
                    {
                        data: 'jasa_kirim',
                        name: 'jasa_kirim',
                        className: 'text-center'
                    },
                    {
                        data: 'nama_kurir',
                        name: 'nama_kurir',
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
