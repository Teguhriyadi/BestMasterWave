@extends('pages.layouts.app')

@push('title_module', 'Detail Kasbon')

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Detail Kasbon
    </h1>

    @if (session('success'))
        <div class="alert alert-success">
            <strong>Berhasil,</strong> {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger">
            <strong>Gagal,</strong> {{ session('error') }}
        </div>
    @endif

    <a href="{{ url('/admin-panel/kasbon') }}" class="btn btn-danger mb-3 btn-sm">
        <i class="fa fa-sign-out-alt"></i> Kembali
    </a>

    <div class="card shadow mb-4">
        <div class="card-body">
            <h4 class="mb-1">{{ $kasbon->karyawan->nama }}</h4>
            <small class="text-muted">
                {{ $kasbon->karyawan->jabatan->nama_jabatan }}
            </small>

            <div class="mt-2">
                <span class="badge badge-{{ $kasbon->status == 'aktif' ? 'success' : 'secondary' }}">
                    {{ strtoupper($kasbon->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary">
                        Jumlah Awal
                    </div>
                    <div class="h5 font-weight-bold">
                        Rp {{ number_format($kasbon->jumlah_awal, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning">
                        Sisa Kasbon
                    </div>
                    <div class="h5 font-weight-bold">
                        Rp {{ number_format($kasbon->sisa, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-info shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info">
                        Tanggal Mulai
                    </div>
                    <div class="h5 font-weight-bold">
                        {{ \Carbon\Carbon::parse($kasbon->tanggal_mulai)->translatedFormat('d F Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($kasbon->status === 'aktif')
        <div class="card shadow mb-4">
            <div class="card-header">
                <strong>Aksi Kasbon</strong>
            </div>
            <div class="card-body">
                {{-- <form action="{{ url('/admin-panel/kasbon/' . $kasbon->id . '/topup') }}" method="POST" class="mb-3">
                    @csrf
                    <h6 class="text-success"><i class="fa fa-plus"></i> Top Up Kasbon</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="number" name="nominal" class="form-control" placeholder="Nominal" required>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="keterangan" class="form-control"
                                placeholder="Keterangan (opsional)">
                        </div>
                    </div>
                    <button class="btn btn-success btn-sm mt-2">
                        <i class="fa fa-save"></i> Simpan Top Up
                    </button>
                </form> --}}

                {{-- <hr> --}}

                <form action="{{ url('/admin-panel/kasbon/' . $kasbon->id . '/bayar') }}" method="POST">
                    @csrf
                    <h6 class="text-warning">
                        <i class="fa fa-minus"></i> Pembayaran Kasbon
                    </h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nominal">
                                    Nominal
                                    <small class="text-danger">*</small>
                                </label>
                                <input type="number" name="nominal" class="form-control" placeholder="Nominal" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nominal">
                                    Metode
                                    <small class="text-danger">*</small>
                                </label>
                                <select name="metode" class="form-control" required>
                                    <option value="">- Metode -</option>
                                    <option value="potong_gaji">Potong Gaji</option>
                                    <option value="cash">Cash</option>
                                    <option value="transfer">Transfer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">
                                    Tanggal Pembayaran
                                    <small class="text-danger">*</small>
                                </label>
                                <input type="date" name="tanggal" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">
                                    Keterangan
                                </label>
                                <input type="text" name="keterangan" class="form-control"
                                placeholder="Keterangan (opsional)">
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-warning btn-sm mt-2">
                        <i class="fa fa-save"></i> Simpan Pembayaran
                    </button>
                </form>

            </div>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header">
            <strong>Riwayat Transaksi</strong>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Nominal</th>
                        <th>Metode</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kasbon->transaksi as $trx)
                        <tr>
                            <td>
                                {{ \Carbon\Carbon::parse($trx->tanggal)->translatedFormat('d F Y') }}
                            </td>
                            <td>
                                <span class="badge badge-{{ $trx->tipe == 'topup' ? 'success' : 'warning' }}">
                                    {{ strtoupper($trx->tipe) }}
                                </span>
                            </td>
                            <td>
                                Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                            </td>
                            <td>{{ $trx->metode ?? '-' }}</td>
                            <td>{{ $trx->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                Belum ada transaksi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endpush
