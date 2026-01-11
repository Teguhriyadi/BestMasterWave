@extends('pages.layouts.app')

@push('title_module', 'Pembelian')

@push("css_style")
    <style>
        .garis-judul {
            width: 15%;
        }
        .garis-tengah {
            text-align: center;
            width: 5%;
        }
    </style>
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Detail Data Pembelian
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
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td class="garis-judul">No. Invoice</td>
                            <td class="garis-tengah">:</td>
                            <td>
                                {{ $detail["no_invoice"] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="garis-judul">Tanggal Invoice</td>
                            <td class="garis-tengah">:</td>
                            <td>
                                {{ $detail["tanggal_invoice"] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="garis-judul">Tanggal Jatuh Tempo</td>
                            <td class="garis-tengah">:</td>
                            <td>
                                {{ $detail["tanggal_jatuh_tempo"] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="garis-judul">Total Harga</td>
                            <td class="garis-tengah">:</td>
                            <td>
                                {{ number_format($detail["total_harga"], 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="garis-judul">Total PPN</td>
                            <td class="garis-tengah">:</td>
                            <td>
                                {{ $detail["total_ppn"] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="garis-judul">Total QTY</td>
                            <td class="garis-tengah">:</td>
                            <td>
                                {{ $detail["total_qty"] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="garis-judul">Nama Supplier</td>
                            <td class="garis-tengah">:</td>
                            <td>
                                {{ $detail["supplier"]["nama_supplier"] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="garis-judul">Keterangan</td>
                            <td class="garis-tengah">:</td>
                            <td>
                                {{ $detail["keterangan"] }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <h5 class="fw-bold">Detail Pembelian Barang</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SKU Barang</th>
                            <th>QTY</th>
                            <th>Satuan</th>
                            <th>Harga Satuan</th>
                            <th>Diskon</th>
                            <th>Total Sebelum PPN</th>
                            <th>PPN</th>
                            <th>Total</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail["details"] as $item)
                            <tr>
                                <td>{{ $item["barang"]["sku_barang"] }}</td>
                                <td>{{ $item["qty"] }}</td>
                                <td>{{ $item["satuan"] }}</td>
                                <td>{{ number_format($item["harga_satuan"], 0, ',', '.') }}</td>
                                <td>{{ number_format($item["diskon"], 0, ',', '.') }}</td>
                                <td>{{ number_format($item["total_sebelum_ppn"], 0, ',', '.') }}</td>
                                <td>{{ number_format($item["ppn"], 0, ',', '.') }}</td>
                                <td>{{ number_format($item["total"], 0, ',', '.') }}</td>
                                <td>{{ $item["keterangan"] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>
@endpush

@push('js_style')

    <script>
        $("form").on("submit", function() {

            $(".harga_satuan").each(function() {
                let val = $(this).val();
                $(this).val(val.replace(/\./g, ""));
            });

            $(".total_sebelum_ppn").each(function() {
                let val = $(this).val();
                $(this).val(val.replace(/\./g, ""));
            });

            $(".total_sesudah_ppn").each(function() {
                let val = $(this).val();
                $(this).val(val.replace(/\./g, ""));
            });

            $("#total_ppn").val($("#total_ppn").val().replace(/\./g, ""));
            $("#total_sebelum_ppn").val($("#total_sebelum_ppn").val().replace(/\./g, ""));
            $("#total_setelah_ppn").val($("#total_setelah_ppn").val().replace(/\./g, ""));
        });
    </script>

@endpush
