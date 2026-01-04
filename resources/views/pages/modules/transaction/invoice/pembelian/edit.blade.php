@extends('pages.layouts.app')

@push('title_module', 'Pembelian')

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Edit Data Pembelian
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
            <a href="{{ url('/admin-panel/pembelian') }}" class="btn btn-danger btn-sm">
                <i class="fa fa-sign-out-alt"></i> Kembali
            </a>
        </div>
        <form action="{{ url('/admin-panel/pembelian/' . $edit['id']) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3 row">
                            <label for="no_invoice" class="col-sm-3 col-form-label">Nomor Invoice</label>
                            <div class="col-md-9">
                                <input type="text" name="no_invoice" class="form-control @error('no_invoice') is-invalid @enderror" id="no_invoice"
                                    placeholder="Masukkan No. Invoice" value="{{ old('no_invoice', $edit['no_invoice']) }}">
                                @error('no_invoice')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="supplier_id" class="col-sm-3 col-form-label">Nama Supplier</label>
                            <div class="col-sm-9">
                                <select name="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror" id="supplier_id">
                                    <option value="">- Pilih -</option>
                                    @foreach ($supplier as $item)
                                        <option value="{{ $item['id'] }}" data-tempo="{{ $item['tempo_pembayaran'] }}"
                                            {{ $edit['supplier_id'] == $item['id'] ? 'selected' : '' }}>
                                            {{ $item['nama_supplier'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="tanggal_invoice" class="col-sm-3 col-form-label">Tanggal Invoice</label>
                            <div class="col-md-9">
                                <input type="date" name="tanggal_invoice @error('tanggal_invoice') is-invalid @enderror" id="tanggal_invoice" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($edit['tanggal_invoice'])->format('Y-m-d') }}">
                                @error('tanggal_invoice')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="tanggal_jatuh_tempo" class="col-sm-3 col-form-label">Tanggal Jatuh Tempo</label>
                            <div class="col-md-9">
                                <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo"
                                    class="form-control @error('tanggal_jatuh_tempo') is-invalid @enderror"
                                    value="{{ \Carbon\Carbon::parse($edit['tanggal_jatuh_tempo'])->format('Y-m-d') }}">
                                @error('tanggal_jatuh_tempo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3 row">
                            <label for="keterangan" class="col-sm-3 col-form-label">
                                Keterangan
                                <small class="text-danger">*</small>
                            </label>
                            <div class="col-sm-9">
                                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" rows="5" placeholder="Masukkan Keterangan">{{ $edit['keterangan'] }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-primary btn-sm" id="btnAddItem">
                    <i class="fa fa-plus"></i> Tambah Data Barang
                </button>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered" id="tableItem">
                        <thead class="thead-light">
                            <tr>
                                <th>SKU Barang</th>
                                <th>QTY</th>
                                <th>Satuan</th>
                                <th>Harga Satuan</th>
                                <th>Diskon</th>
                                <th>PPN</th>
                                <th>Total</th>
                                <th>Keterangan</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody id="itemBody">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <button type="reset" class="btn btn-secondary btn-sm">
                    <i class="fa fa-times"></i> Batalkan
                </button>
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
@endpush

@push('js_style')
    <script type="text/javascript">
        const barangList = @json($barang);
        let itemIndex = 0;
        const detailPembelian = @json($edit->details);

        $(document).ready(function() {
            detailPembelian.forEach(item => renderRow(item));
            hitungTanggalJatuhTempo()
        })

        $("#btnAddItem").on("click", function() {
            renderRow();
        });

        function renderRow(data = null) {
            itemIndex++;

            let options = `<option value="">- Pilih Barang -</option>`;
            barangList.forEach(b => {
                let selected = data && data.sku_barang == b.id ? 'selected' : '';
                options += `
            <option value="${b.id}"
                    data-harga="${b.harga_modal}"
                    ${selected}>
                ${b.sku_barang}
            </option>`;
            });

            let row = `
                <tr id="row-${itemIndex}">
                    <input type="hidden"
                        name="items[${itemIndex}][id]"
                        value="${data?.id ?? ''}">

                    <td>
                        <select name="items[${itemIndex}][barang_id]"
                                class="form-control barang-select" required>
                            ${options}
                        </select>
                    </td>

                    <td>
                        <input type="number"
                            name="items[${itemIndex}][qty]"
                            class="form-control qty"
                            value="${data?.qty ?? 1}" required>
                    </td>

                    <td>
                        <input type="text"
                            name="items[${itemIndex}][satuan]"
                            class="form-control satuan"
                            value="${data?.satuan ?? 'set'}" required>
                    </td>

                    <td>
                        <input type="number"
                            name="items[${itemIndex}][harga_satuan]"
                            class="form-control harga_satuan"
                            value="${data?.harga_satuan ?? 0}" required>
                    </td>

                    <td>
                        <input type="number"
                            name="items[${itemIndex}][diskon]"
                            class="form-control diskon"
                            value="${data?.diskon ?? 0}">
                    </td>

                    <td>
                        <input type="number"
                            name="items[${itemIndex}][ppn]"
                            class="form-control ppn"
                            value="${data?.ppn ?? 0}">
                    </td>

                    <td>
                        <input type="number"
                            name="items[${itemIndex}][total_harga]"
                            class="form-control total_harga"
                            value="${data?.total_harga ?? 0}">
                    </td>

                    <td>
                        <input type="text"
                            name="items[${itemIndex}][keterangan]"
                            class="form-control"
                            value="${data?.keterangan ?? ''}">
                    </td>

                    <td>
                        <button type="button"
                                class="btn btn-danger btn-sm"
                                onclick="$('#row-${itemIndex}').remove()">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>`;

            $("#itemBody").append(row);
        }


        $(document).on("input change", ".qty, .harga_satuan, .diskon, .ppn", function() {
            let row = $(this).closest("tr");
            let qty = +row.find(".qty").val() || 0;
            let harga = +row.find(".harga_satuan").val() || 0;
            let diskon = +row.find(".diskon").val() || 0;
            let ppn = +row.find(".ppn").val() || 0;
            row.find(".total_harga").val((qty * harga) - diskon + ppn);
        });

        $(document).on("change", ".barang-select", function() {
            let harga = $(this).find(":selected").data("harga") || 0;
            let row = $(this).closest("tr");
            row.find(".harga_satuan").val(harga).trigger("input");
        });

        function hitungTanggalJatuhTempo() {
            let tempo = +$("#supplier_id option:selected").data("tempo") || 0;
            let tgl = $("#tanggal_invoice").val();
            if (!tgl) return;

            let d = new Date(tgl);
            d.setDate(d.getDate() + tempo);

            $("#tanggal_jatuh_tempo").val(
                d.toISOString().slice(0, 10)
            );
        }

        $("#supplier_id, #tanggal_invoice").on("change", hitungTanggalJatuhTempo);
    </script>
@endpush
