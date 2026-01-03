@extends('pages.layouts.app')

@push('title_module', 'Users')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet">

    <style>
        .spacing-title {
            width: 20%;
        }

        .spacing {
            width: 2%;
            text-align: center;
        }
    </style>
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Detail Data
        <strong>
            {{ $detail['nama'] }}
        </strong>
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

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <a href="{{ url('/admin-panel/users') }}" class="btn btn-danger btn-sm">
                        <i class="fa fa-sign-out-alt"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td class="spacing-title">Nama</td>
                                <td class="spacing">:</td>
                                <td>
                                    {{ $detail["nama"] }}
                                </td>
                            </tr>
                            <tr>
                                <td class="spacing-title">Username</td>
                                <td class="spacing">:</td>
                                <td>
                                    {{ $detail["username"] }}
                                </td>
                            </tr>
                            <tr>
                                <td class="spacing-title">Email</td>
                                <td class="spacing">:</td>
                                <td>
                                    {{ $detail["email"] }}
                                </td>
                            </tr>
                            <tr>
                                <td class="spacing-title">Nomor Handphone</td>
                                <td class="spacing">:</td>
                                <td>
                                    {{ $detail["nomor_handphone"] }}
                                </td>
                            </tr>
                            <tr>
                                <td class="spacing-title">Alamat</td>
                                <td class="spacing">:</td>
                                <td>
                                    {{ $detail["alamat"] }}
                                </td>
                            </tr>
                            <tr>
                                <td class="spacing-title">Status Akun</td>
                                <td class="spacing">:</td>
                                <td>
                                    @if ($detail["is_active"] == "1")
                                        <span class="badge bg-success text-white">
                                            Aktif
                                        </span>
                                    @elseif ($detail["is_active"] == "0")
                                        <span class="badge bg-danger text-white">
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="spacing-title">Role Akun</td>
                                <td class="spacing">:</td>
                                <td>
                                    @foreach ($detail["divisiRoles"] as $item)
                                        <span class="badge bg-success text-white">
                                            {{ $item['divisi']["nama_divisi"] }} - {{ $item["roles"]["nama_role"] }}
                                        </span>
                                    @endforeach
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endpush
