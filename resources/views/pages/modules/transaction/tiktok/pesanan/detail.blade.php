@extends('pages.layouts.app')

@push('title_module', 'Tiktok Pesanan')

@push('css_style')
    <style>
        .judul {
            width: 20%;
        }

        .pemisah {
            width: 10px;
            text-align: center;
        }
    </style>
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Detail Data Pesanan Tiktok
    </h1>

    <a href="{{ url('/admin-panel/tiktok-pesanan/data') }}" class="btn btn-danger btn-sm mb-4">
        <i class="fa fa-sign-out-alt"></i> Kembali
    </a>

    @if (session('success'))
        <div class="alert alert-success">
            <strong>Berhasil,</strong> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Data Pesanan</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    @foreach ($details as $row)
                        <tr>
                            <td class="judul">{{ $row['label'] }}</td>
                            <td class="pemisah">:</td>
                            <td>{{ $row['value'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endpush
