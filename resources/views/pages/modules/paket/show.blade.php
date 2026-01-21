@extends('pages.layouts.app')

@push('title_module', 'Paket')

@push("css_style")
    <style>
        .titik-dua {
            width: 5%;
            text-align: center;
        }

        .header-komponen {
            width: 60%;
            font-weight: bold;
        }
    </style>
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Detail Data Paket
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
        <div class="card-body">

            <table class="mb-3" cellpadding="5">
                <tbody>
                    <tr>
                        <td class="header-komponen">SKU Paket</td>
                        <td class="titik-dua">:</td>
                        <td>
                            {{ $detail['sku_paket'] }}
                        </td>
                    </tr>

                    <tr>
                        <td class="header-komponen">Nama Paket</td>
                        <td class="titik-dua">:</td>
                        <td>
                            {{ $detail['nama_paket'] }}
                        </td>
                    </tr>

                    <tr>
                        <td class="header-komponen">Nama Seller</td>
                        <td class="titik-dua">:</td>
                        <td>
                           {{ empty($detail['seller']['nama_seller']) ? "-" : $detail['seller']['nama_seller'] }}
                        </td>
                    </tr>

                    <tr>
                        <td class="header-komponen">Harga Modal Paket</td>
                        <td class="titik-dua">:</td>
                        <td>
                            {{ number_format($detail->harga_jual, 0, ',', '.'), }}
                        </td>
                    </tr>

                    <tr>
                        <td class="header-komponen">Status Paket</td>
                        <td class="titik-dua">:</td>
                        <td>
                            @if ($detail['status'] == "A")
                                <span class="badge bg-success text-white text-uppercase">
                                    Aktif
                                </span>
                            @else
                                <span class="badge bg-danger text-white text-uppercase">
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            <hr>

            <h4 class="mb-4">
                <i class="fa fa-book"></i> Detail Data Komponen Paket
            </h4>

            <table class="table table-bordered nowrap" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">SKU Barang</th>
                        <th class="text-center">QTY</th>
                        <th class="text-center">Harga Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detail['items'] as $item)
                        <tr>
                            <td class="text-center">{{ $item['barangs']['sku_barang'] }}</td>
                            <td class="text-center">{{ $item['qty'] }}</td>
                            <td class="text-center">{{ number_format($item->harga_satuan, 0, ',', '.'), }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endpush
