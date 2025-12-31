@extends('pages.layouts.app')

@push('title_module', 'Pembelian')

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Tambah Data Pembelian
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
        <form action="{{ url('/admin-panel/pembelian') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="mb-3 row">
                    <label for="no_invoice" class="col-sm-2 col-form-label">Nomor Invoice</label>
                    <div class="col-sm-4">
                        <input type="text" name="no_invoice" class="form-control" id="no_invoice"
                            placeholder="Masukkan No. Invoice">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="supplier_id" class="col-sm-2 col-form-label">Nama Supplier</label>
                    <div class="col-sm-6">
                        <select name="supplier_id" class="form-control" id="supplier_id">
                            <option value="">- Pilih -</option>
                            @foreach ($supplier as $item)
                                <option value="{{ $item['id'] }}" data-tempo="{{ $item['tempo_pembayaran'] }}">
                                    {{ $item['nama_supplier'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="tanggal_invoice" class="col-sm-2 col-form-label">Tanggal Invoice</label>
                    <div class="col-sm-4">
                        <input type="date" name="tanggal_invoice" class="form-control" id="tanggal_invoice">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="tanggal_jatuh_tempo" class="col-sm-2 col-form-label">Tanggal Jatuh Tempo</label>
                    <div class="col-sm-4">
                        <input type="date" name="tanggal_jatuh_tempo" class="form-control" id="tanggal_jatuh_tempo">
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm" id="btnAddItem">
                    <i class="fa fa-plus"></i> Tambah Data Barang
                </button>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered" id="tableItem" style="display:none;">
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

        $("#btnAddItem").on("click", function(e) {
            e.preventDefault()

            $("#tableItem").show();

            itemIndex++;

            let options = `<option value="">- Pilih Barang -</option>`;
            barangList.forEach(item => {
                options += `
                    <option value="${item.id}"
                            data-harga="${item.harga_modal ?? 0}"
                            data-satuan="${item.satuan ?? ''}">
                        ${item.sku_barang}
                    </option>
                `;
            });

            let row = `
                <tr id="row-${itemIndex}">
                    <td>
                        <select name="items[${itemIndex}][barang_id]"
                                class="form-control barang-select">
                            ${options}
                        </select>
                    </td>
                    <td>
                        <input type="number"
                            name="items[${itemIndex}][qty]"
                            class="form-control qty"
                            min="1">
                    </td>
                    <td>
                        <select class="form-control satuan" name="items[${itemIndex}][satuan]">
                            <option value="">- Pilih Satuan -</option>
                            <option value="set">Set</option>
                        </select>
                    </td>
                    <td>
                        <input type="number"
                            name="items[${itemIndex}][harga_satuan]"
                            class="form-control harga_satuan">
                    </td>
                    <td>
                        <input type="number"
                            name="items[${itemIndex}][diskon]"
                            class="form-control diskon">
                    </td>
                    <td>
                        <input type="number"
                            name="items[${itemIndex}][ppn]"
                            class="form-control ppn">
                    </td>
                    <td>
                        <input type="number"
                            name="items[${itemIndex}][total_harga]"
                            class="form-control total_harga">
                    </td>
                    <td>
                        <input type="text"
                            name="items[${itemIndex}][keterangan]"
                            class="form-control keterangan" placeholder="Masukkan Keterangan">
                    </td>
                    <td class="text-center">
                        <button type="button"
                                class="btn btn-danger btn-sm"
                                onclick="removeRow(${itemIndex})">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            $("#itemBody").append(row);
        })

        function removeRow(id) {
            $("#row-" + id).remove();

            if ($("#itemBody tr").length === 0) {
                $("#tableItem").hide();
            }
        }

        $(document).on("change", ".barang-select", function() {
            let row = $(this).closest("tr");
            let selected = $(this).find(":selected");

            let hargaModal = parseFloat(selected.data("harga")) || 0;

            row.find(".harga_satuan").val(hargaModal);

            hitungTotal(row);
        });

        $(document).on("input", ".qty, .harga_satuan, .diskon, .ppn", function() {
            let row = $(this).closest("tr");
            hitungTotal(row);
        });

        function hitungTotal(row) {
            let qty = parseFloat(row.find(".qty").val()) || 0;
            let hargaSatuan = parseFloat(row.find(".harga_satuan").val()) || 0;
            let diskon = parseFloat(row.find(".diskon").val()) || 0;
            let ppn = parseFloat(row.find(".ppn").val()) || 0;

            let subtotal = qty * hargaSatuan;
            let total = subtotal - diskon + ppn;

            row.find(".total_harga").val(total);
        }


        function editSupplier(id) {
            $.ajax({
                url: "{{ url('/admin-panel/supplier') }}" + "/" + id + "/edit",
                type: "GET",
                success: function(response) {
                    $("#modal-content-edit").html(response)
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    </script>

    <script>
        function hitungTanggalJatuhTempo() {
            let supplier = $("#supplier_id option:selected");
            let tempo = parseInt(supplier.data("tempo")) || 0;

            let tanggalInvoice = $("#tanggal_invoice").val();

            if (!tanggalInvoice || tempo <= 0) {
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

        $("#supplier_id").on("change", hitungTanggalJatuhTempo);

        $("#tanggal_invoice").on("change", hitungTanggalJatuhTempo);
    </script>
@endpush
