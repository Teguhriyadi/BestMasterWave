@extends('pages.layouts.app')

@push('title_module', 'Paket')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Tambah Data Paket
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
            <a href="{{ url('/admin-panel/paket') }}" class="btn btn-danger btn-sm">
                <i class="fa fa-sign-out-alt"></i> Kembali
            </a>
        </div>
        <form action="{{ url('/admin-panel/paket') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <h5 class="font-weight-bold">Informasi Utama</h5>
                        <hr>
                        <div class="form-group">
                            <label>SKU Paket <span class="text-danger">*</span></label>
                            <input type="text" name="sku_paket" class="form-control @error("sku_paket") is-invalid @enderror" placeholder="Contoh: PKT-HEM-01" value="{{ old('sku_paket') }}">

                            @error('sku_paket')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Nama Paket <span class="text-danger">*</span></label>
                            <input type="text" name="nama_paket" class="form-control @error("nama_paket") is-invalid @enderror"
                                placeholder="Contoh: Paket Hemat Sembako" value="{{ old('nama_paket') }}">

                            @error('nama_paket')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Harga Jual Paket (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga_paket" id="harga_paket" class="form-control @error("harga_paket") is-invalid @enderror" placeholder="0" value="{{ old('harga_paket') }}">
                            <small class="text-muted">Tentukan harga jual akhir untuk pembeli.</small>

                            @error('harga_paket')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Nama Seller <span class="text-danger">*</span></label>
                            <select name="seller_id" class="form-control @error('seller_id') is-invalid @enderror" id="seller_id">
                                <option value="">- Pilih -</option>
                                @foreach ($seller as $item)
                                    <option {{ old('seller_id') == $item['id'] ? 'selected' : '' }} value="{{ $item['id'] }}">
                                        {{ $item['nama'] }}
                                    </option>
                                @endforeach
                            </select>

                            @error('seller_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-7">
                        <h5 class="font-weight-bold">Paket Barang</h5>
                        <hr>
                        <table class="table table-bordered" id="tableItemPaket">
                            <thead class="thead-light">
                                <tr>
                                    <th>Pilih Barang</th>
                                    <th width="120">Qty</th>
                                    <th width="100">Harga</th>
                                    <th width="50"></th>
                                </tr>
                            </thead>
                            <tbody id="bundle-container">
                                <tr class="bundle-item">
                                    <td style="width: 50%">
                                        <select name="barang_id[]" class="form-control select2-barang" required>
                                            <option value="">-- Pilih Barang --</option>
                                            @foreach ($barangs as $b)
                                                <option value="{{ $b['id'] }}">
                                                    {{ $b['sku_barang'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="qty[]" class="form-control" value="1"
                                            min="1" required>
                                    </td>
                                    <td>
                                        <input type="number" name="harga[]" class="form-control" value="1"
                                            min="1" required>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-item">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" id="add-item" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Tambah Barang
                        </button>

                        <div class="mt-4 p-3 bg-light border rounded">
                            <p class="mb-0"><strong>Tips:</strong> Stok paket akan otomatis mengikuti stok barang yang
                                paling sedikit ketersediaannya.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="reset" class="btn btn-secondary btn-sm">
                    <i class="fa fa-times"></i> Batal
                </button>
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
@endpush

@push('js_style')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#seller_id').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Seller',
                allowClear: true,
                width: '100%'
            });

            $('#add-item').click(function() {
                var newRow = `
                <tr class="bundle-item">
                    <td style="width:50%">
                        <select name="barang_id[]" class="form-control" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach ($barangs as $b)
                                <option value="{{ $b['id'] }}">{{ $b['sku_barang'] }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="qty[]" class="form-control" value="1" min="1" required>
                    </td>
                    <td>
                        <input type="number" name="harga[]" class="form-control" value="1" min="1" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-item">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
                $('#bundle-container').append(newRow);
            });

            $(document).on('click', '.remove-item', function() {
                if ($('.bundle-item').length > 1) {
                    $(this).closest('tr').remove();
                } else {
                    alert("Minimal harus ada 1 barang dalam paket!");
                }
            });
        });
    </script>
@endpush
