@extends('pages.layouts.app')

@push('title_module', 'Pembelian')

@push('css_style')
    <style>
        .table-scroll-x {
            overflow-x: auto;
            width: 100%;
        }

        #tableItem {
            white-space: nowrap;
        }
    </style>
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Tambah Data Pembelian
    </h1>

    @if (session('success'))
        <div class="alert alert-success">
            <strong>Berhasil</strong>, {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            <strong>Gagal</strong>, {{ session('error') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{ url('/admin-panel/pembelian') }}" class="btn btn-danger btn-sm">
                <i class="fa fa-sign-out-alt"></i> Kembali
            </a>
        </div>
        <form action="{{ url('/admin-panel/pembelian') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3 row">
                            <label for="no_invoice" class="col-sm-3 col-form-label">
                                Nomor Invoice
                                <small class="text-danger">*</small>
                            </label>
                            <div class="col-sm-9">
                                <input type="text" name="no_invoice"
                                    class="form-control @error('no_invoice') is-invalid @enderror" id="no_invoice"
                                    placeholder="Masukkan No. Invoice" value="{{ old('no_invoice') }}">
                                @error('no_invoice')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="supplier_id" class="col-sm-3 col-form-label">
                                Nama Supplier
                                <small class="text-danger">*</small>
                            </label>
                            <div class="col-sm-9">
                                <select name="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror"
                                    id="supplier_id">
                                    <option value="">- Pilih -</option>
                                    @foreach ($supplier as $item)
                                        <option value="{{ $item['id'] }}"
                                            {{ old('supplier_id') == $item['id'] ? 'selected' : '' }}
                                            data-tempo="{{ $item['tempo_pembayaran'] }}" data-ppn="{{ $item['ppn'] }}">
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
                            <label for="tanggal_invoice" class="col-sm-3 col-form-label">
                                Tanggal Invoice
                                <small class="text-danger">*</small>
                            </label>
                            <div class="col-sm-9">
                                <input type="date" name="tanggal_invoice"
                                    class="form-control @error('tanggal_invoice') is-invalid @enderror" id="tanggal_invoice"
                                    value="{{ old('tanggal_invoice') }}">
                                @error('tanggal_invoice')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="tanggal_jatuh_tempo" class="col-sm-3 col-form-label">
                                Tanggal Jatuh Tempo
                                <small class="text-danger">*</small>
                            </label>
                            <div class="col-sm-9">
                                <input type="date" name="tanggal_jatuh_tempo"
                                    class="form-control @error('tanggal_jatuh_tempo') is-invalid @enderror"
                                    id="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo') }}">
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
                            </label>
                            <div class="col-sm-9">
                                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" id="keterangan"
                                    rows="5" placeholder="Masukkan Keterangan">{{ old('keterangan') }}</textarea>
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

                <div class="table-responsive mt-3 table-scroll-x">
                    <table class="table table-bordered" id="tableItem" style="display:none;">
                        <thead class="thead-light">
                            <tr>
                                <th>
                                    SKU Barang
                                    <small class="text-danger">*</small>
                                </th>
                                <th>
                                    QTY
                                    <small class="text-danger">*</small>
                                </th>
                                <th>
                                    Satuan
                                    <small class="text-danger">*</small>
                                </th>
                                <th>
                                    Harga Satuan
                                    <small class="text-danger">*</small>
                                </th>
                                <th>Diskon</th>
                                <th>PPN</th>
                                <th>Total Sebelum PPN</th>
                                <th>Total Setelah PPN</th>
                                <th>Keterangan</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody id="itemBody">

                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4"></th>
                                <th>
                                    <input type="number" id="total_diskon" class="form-control" readonly>
                                </th>
                                <th>
                                    <input type="number" id="total_ppn" class="form-control" readonly>
                                </th>
                                <th>
                                    <input type="number" id="total_sebelum_ppn" class="form-control" readonly>
                                </th>
                                <th>
                                    <input type="number" id="total_setelah_ppn" class="form-control" readonly>
                                </th>
                            </tr>
                        </tfoot>

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
    <script>
        const barangList = @json($barang);
        let itemIndex = 0;
        let supplierRatePPN = 0;

        $("#btnAddItem").on("click", function(e) {
            e.preventDefault();
            $("#tableItem").show();
            itemIndex++;

            let options = `<option value="">- Pilih Barang -</option>`;
            barangList.forEach(item => {
                options += `
                <option value="${item.id}"
                        data-harga="${item.harga_modal ?? 0}"
                        data-satuan="${item.satuan ?? ''}">
                    ${item.sku_barang}
                </option>`;
            });

            let row = `
        <tr id="row-${itemIndex}">
            <td>
                <select name="items[${itemIndex}][barang_id]"
                        class="form-control barang-select" required>
                    ${options}
                </select>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][qty]"
                       class="form-control qty" min="1" required placeholder="0">
            </td>
            <td>
                <select class="form-control" name="items[${itemIndex}][satuan]">
                    <option>- Pilih -</option>
                    <option value="Set">Set</option>
                </select>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][harga_satuan]"
                       class="form-control harga_satuan" required placeholder="0">
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][diskon]"
                       class="form-control diskon" value="0">
            </td>
            <td>
                <input type="number"
                       name="items[${itemIndex}][rate_ppn]"
                       class="form-control rate_ppn" placeholder="0">
            </td>
            <td>
                <input type="number"
                       name="items[${itemIndex}][total_sebelum_ppn]"
                       class="form-control total_sebelum_ppn" placeholder="0">
            </td>
            <td>
                <input type="number"
                       name="items[${itemIndex}][total_sesudah_ppn]"
                       class="form-control total_sesudah_ppn" placeholder="0">
            </td>
            <td>
                <input type="text"
                       name="items[${itemIndex}][keterangan]"
                       class="form-control" placeholder="Masukkan Keterangan">
            </td>
            <td class="text-center">
                <button type="button"
                        class="btn btn-danger btn-sm"
                        onclick="removeRow(${itemIndex})">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>`;
            $("#itemBody").append(row);
        });

        function removeRow(id) {
            $("#row-" + id).remove();
            if ($("#itemBody tr").length === 0) {
                $("#tableItem").hide();
                $("#total_diskon,#total_ppn,#total_sebelum_ppn,#total_setelah_ppn").val(0);
            } else {
                hitungTotalAllIn();
            }
        }

        $(document).on("change", ".barang-select", function() {
            let row = $(this).closest("tr");
            let harga = parseFloat($(this).find(":selected").data("harga")) || 0;
            let satuan = $(this).find(":selected").data("satuan") || "";

            row.find(".harga_satuan").val(harga);
            row.find(".satuan").val(satuan);
            row.find(".rate_ppn").val(supplierRatePPN);

            hitungTotal(row);
        });

        $(document).on("input", ".qty,.harga_satuan,.diskon", function() {
            hitungTotal($(this).closest("tr"));
        });

        function hitungTotal(row) {
            let qty = parseFloat(row.find(".qty").val()) || 0;
            let harga = parseFloat(row.find(".harga_satuan").val()) || 0;
            let diskon = parseFloat(row.find(".diskon").val()) || 0;
            let ratePPN = parseFloat(row.find(".rate_ppn").val()) || 0;

            let subtotal = qty * harga;
            let totalSebelumPPN = subtotal - diskon;
            if (totalSebelumPPN < 0) totalSebelumPPN = 0;

            let ppnNominal = totalSebelumPPN * ratePPN / 100;

            row.find(".total_sebelum_ppn").val(Math.round(totalSebelumPPN));
            row.find(".total_sesudah_ppn").val(Math.round(ppnNominal)); // 👈 FIX DI SINI

            hitungTotalAllIn();
        }

        function hitungTotalAllIn() {
            let totalDiskon = 0;
            let totalSebelumPPN = 0;
            let totalSetelahPPN = 0;

            $("#itemBody tr").each(function() {
                totalDiskon += parseFloat($(this).find(".diskon").val()) || 0;
                totalSebelumPPN += parseFloat($(this).find(".total_sebelum_ppn").val()) || 0;
                totalSetelahPPN += parseFloat($(this).find(".total_sesudah_ppn").val()) || 0;
            });

            $("#total_diskon").val(Math.round(totalDiskon));
            $("#total_ppn").val(supplierRatePPN); // 👈 FIX: TETAP 20
            $("#total_sebelum_ppn").val(Math.round(totalSebelumPPN));
            $("#total_setelah_ppn").val(Math.round(totalSetelahPPN));
        }

        $("#supplier_id").on("change", function() {
            supplierRatePPN = parseFloat($(this).find(":selected").data("ppn")) || 0;
            hitungTanggalJatuhTempo();

            $("#itemBody tr").each(function() {
                $(this).find(".rate_ppn").val(supplierRatePPN);
                hitungTotal($(this));
            });

            $("#total_ppn").val(supplierRatePPN);
        });

        $("#tanggal_invoice").on("change", function() {
            hitungTanggalJatuhTempo();
        });

        function hitungTanggalJatuhTempo() {
            let tempo = parseInt($("#supplier_id").find(":selected").data("tempo")) || 0;
            let tanggalInvoice = $("#tanggal_invoice").val();

            if (!tanggalInvoice) {
                $("#tanggal_jatuh_tempo").val("");
                return;
            }

            let date = new Date(tanggalInvoice);
            date.setDate(date.getDate() + tempo);

            let yyyy = date.getFullYear();
            let mm = String(date.getMonth() + 1).padStart(2, '0');
            let dd = String(date.getDate()).padStart(2, '0');

            $("#tanggal_jatuh_tempo").val(`${yyyy}-${mm}-${dd}`);
        }
    </script>

@endpush
