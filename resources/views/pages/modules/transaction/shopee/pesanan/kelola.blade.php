@extends('pages.layouts.app')

@push('title_module', 'List Data Shopee Pesanan')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet">
@endpush

@push('content_app')
    <h1 class="h3 mb-4 text-gray-800">Data Pesanan Shopee</h1>

    <a href="{{ url('/admin-panel/shopee-pesanan') }}" class="btn btn-danger btn-sm mb-4">
        <i class="fa fa-sign-out-alt"></i> Kembali
    </a>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List Data Pesanan</h6>
        </div>
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
                    <div class="col-md-2">
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
                            <th>Nama Seller</th>
                            <th>No. Referensi SKU</th>
                            <th>Nama Produk</th>
                            <th class="text-center">Waktu Dibuat</th>
                            <th class="text-center">Waktu Bayar</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Harga Modal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalHargaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form id="formHargaModal">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fa fa-edit"></i> Ubah Harga Modal
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="jumlah" id="jumlah">
                        <input type="hidden" name="sku" id="sku">

                        <div id="harga-modal-content">
                            <div class="text-center text-muted">
                                <i class="fa fa-spinner fa-spin"></i> Memuat data...
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="reset" class="btn btn-danger btn-sm">
                            <i class="fa fa-times"></i> RESET
                        </button>
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fa fa-save"></i> SIMPAN
                        </button>
                    </div>
                </div>
            </form>
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
                        data: 'nama_seller',
                        name: 'nama_seller'
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
                        data: 'harga_modal',
                        name: 'harga_modal',
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

        $(document).on('click', '.btn-modal-harga', function() {
            let sku = $(this).data('sku');
            let jumlah = $(this).data('jumlah');

            $('#jumlah').val(jumlah)
            $('#sku').val(sku);
            $('#modalHargaModal').modal('show');

            $('#harga-modal-content').html(`
                <div class="text-center text-muted">
                    <i class="fa fa-spinner fa-spin"></i> Memuat data...
                </div>
            `);

            $.get(`{{ url('/admin-panel/shopee-pesanan/${jumlah}/${sku}/harga-modal') }}`, function(res) {
                $('#harga-modal-content').html(res);
            });
        });

        $('#formHargaModal').on('submit', function(e) {
            e.preventDefault();

            let sku = $('#sku').val();
            let harga_modal = $('#harga_modal').val();
            let harga_pembelian_terakhir = $('#harga_pembelian_terakhir').val();
            let status_sku = $('#status_sku').val();
            let tanggal_pembelian_terakhir = $('#tanggal_pembelian_terakhir').val();

            let msg = `
SKU: ${sku}
Harga Modal: ${harga_modal}
Harga Pembelian Terakhir: ${harga_pembelian_terakhir}
Status SKU: ${status_sku}
Tanggal Pembelian Terakhir: ${tanggal_pembelian_terakhir}

Apakah Anda yakin ingin menyimpan perubahan ini?
    `;

            if (confirm(msg)) {
                $.ajax({
                    url: "{{ url('/admin-panel/shopee-pesanan/harga-modal/tambah') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        alert(res.message);
                        $('#modalHargaModal').modal('hide');
                    },
                    error: function(err) {
                        alert(err.responseJSON?.message || 'Terjadi kesalahan');
                    }
                });
            } else {
                return false;
            }
        });
    </script>
@endpush
