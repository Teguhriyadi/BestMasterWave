@extends('pages.layouts.app')

@push('title_module', 'Karyawan')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('content_app')


    <h1 class="h3 mb-4 text-gray-800">
        Data Karyawan
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

    <div class="row">
        <div class="col-md-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <a href="{{ url('/admin-panel/karyawan') }}" class="btn btn-danger btn-sm">
                        <i class="fa fa-sign-out-alt"></i> Kembali
                    </a>
                </div>
                <form action="{{ url('/admin-panel/karyawan/' . $edit['id']) }}" method="POST">
                    @csrf
                    @method("PUT")
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label for="id_sidik_jari" class="col-sm-3 col-form-label">
                                ID Sidik Jari
                            </label>
                            <div class="col-sm-4">
                                <input type="text" name="id_sidik_jari" class="form-control @error('id_sidik_jari') is-invalid @enderror" id="id_sidik_jari"
                                    placeholder="Masukkan ID Sidik Jari"
                                    value="{{ old('id_sidik_jari', $edit['id_sidik_jari']) }}">

                                @error('id_sidik_jari')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="no_ktp" class="col-sm-3 col-form-label">
                                Nomor KTP
                            </label>
                            <div class="col-sm-3">
                                <input type="text" name="no_ktp" class="form-control @error('no_ktp') is-invalid @enderror" id="no_ktp"
                                    placeholder="Masukkan Nomor KTP" value="{{ old('no_ktp', $edit['no_ktp']) }}">

                                @error('no_ktp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="no_kk" class="col-sm-3 col-form-label">
                                Nomor Kartu Keluarga
                            </label>
                            <div class="col-sm-3">
                                <input type="text" name="no_kk" class="form-control @error('no_kk') is-invalid @enderror" id="no_kk"
                                    placeholder="Masukkan Nomor Kartu Keluarga" value="{{ old('no_kk', $edit['no_kk']) }}">

                                @error('no_kk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="no_bpjs_kesehatan" class="col-sm-3 col-form-label">
                                Nomor BPJS Kesehatan
                            </label>
                            <div class="col-sm-4">
                                <input type="text" name="no_bpjs_kesehatan" class="form-control @error('no_bpjs_kesehatan') is-invalid @enderror" id="no_bpjs_kesehatan"
                                    placeholder="Masukkan Nomor BPJS Kesehatan"
                                    value="{{ old('no_bpjs_kesehatan', $edit['no_bpjs_kesehatan']) }}">

                                @error('no_bpjs_kesehatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="nama" class="col-sm-3 col-form-label">
                                Nama Lengkap
                                <small class="text-danger">*</small>
                            </label>
                            <div class="col-sm-5">
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                    placeholder="Masukkan Nama Lengkap" value="{{ old('nama', $edit['nama']) }}">

                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="nama_panggilan" class="col-sm-3 col-form-label">
                                Nama Panggilan
                                <small class="text-danger">*</small>
                            </label>
                            <div class="col-sm-4">
                                <input type="text" name="nama_panggilan" class="form-control @error('nama_panggilan') is-invalid @enderror" id="nama_panggilan"
                                    placeholder="Masukkan Nama Panggilan"
                                    value="{{ old('nama_panggilan', $edit['nama_panggilan']) }}">

                                @error('nama_panggilan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="tanggal_masuk" class="col-sm-3 col-form-label">
                                Tanggal Masuk
                                <small class="text-danger">*</small>
                            </label>
                            <div class="col-sm-4">
                                <input type="date" name="tanggal_masuk" class="form-control @error('tanggal_masuk') is-invalid @enderror" id="tanggal_masuk"
                                    value="{{ old('tanggal_masuk', $edit['tanggal_masuk']) }}">

                                @error('tanggal_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="tanggal_keluar" class="col-sm-3 col-form-label">
                                Tanggal Keluar
                            </label>
                            <div class="col-sm-4">
                                <input type="date" name="tanggal_keluar" class="form-control @error('tanggal_keluar') is-invalid @enderror" id="tanggal_keluar"
                                    value="{{ old('tanggal_keluar', $edit['tanggal_keluar']) }}">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="no_hp" class="col-sm-3 col-form-label">
                                Nomor Handphone
                                <small class="text-danger">*</small>
                            </label>
                            <div class="col-sm-4">
                                <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp"
                                    placeholder="Masukkan Nomor Handphone" value="{{ old('no_hp', $edit['no_hp']) }}">

                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="no_hp_darurat" class="col-sm-3 col-form-label">
                                Nomor Handphone Darurat
                                <small class="text-danger">*</small>
                            </label>
                            <div class="col-sm-4">
                                <input type="text" name="no_hp_darurat" class="form-control @error('no_hp_darurat') is-invalid @enderror" id="no_hp_darurat"
                                    placeholder="Masukkan Nomor Handphone Darurat"
                                    value="{{ old('no_hp_darurat', $edit['no_hp_darurat']) }}">

                                @error('no_hp_darurat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="tempat_lahir" class="col-sm-3 col-form-label">
                                Tempat Lahir
                                <small class="text-danger">*</small>
                            </label>
                            <div class="col-sm-4">
                                <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir"
                                    placeholder="Masukkan Tempat Lahir"
                                    value="{{ old('tempat_lahir', $edit['tempat_lahir']) }}">

                                @error('tempat_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="tanggal_lahir" class="col-sm-3 col-form-label">
                                Tanggal Lahir
                                <small class="text-danger">*</small>
                            </label>
                            <div class="col-sm-4">
                                <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir"
                                    value="{{ old('tanggal_lahir', $edit['tanggal_lahir']) }}">

                                @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="jenis_kelamin" class="col-sm-3 col-form-label">
                                Jenis Kelamin
                                <small class="text-danger">*</small>
                            </label>
                            <div class="col-sm-4">
                                <select name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin">
                                    <option value="">- Pilih -</option>
                                    <option
                                        {{ old('jenis_kelamin', $edit['jenis_kelamin'] ?? '') == 'L' ? 'selected' : '' }}
                                        value="L">Laki - Laki
                                    </option>
                                    <option
                                        {{ old('jenis_kelamin', $edit['jenis_kelamin'] ?? '') == 'P' ? 'selected' : '' }}
                                        value="P">Perempuan
                                    </option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="alamat" class="col-sm-3 col-form-label">
                                Alamat
                                <small class="text-danger">*</small>
                            </label>
                            <div class="col-sm-6">
                                <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" id="alamat" rows="5" placeholder="Masukkan Alamat">{{ old('alamat', $edit['alamat']) }}</textarea>

                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="status_pernikahan" class="col-sm-3 col-form-label">
                                Status Pernikahan
                                <small class="text-danger">*</small>
                            </label>
                            <div class="col-sm-4">
                                <select name="status_pernikahan" class="form-control @error('status_pernikahan') is-invalid @enderror" id="status_pernikahan">
                                    <option value="">- Pilih -</option>
                                    <option value="Sudah Menikah"
                                        {{ old('status_pernikahan', $edit['status_pernikahan'] ?? '') == 'Sudah Menikah' ? 'selected' : '' }}>
                                        Sudah Menikah
                                    </option>

                                    <option value="Belum Menikah"
                                        {{ old('status_pernikahan', $edit['status_pernikahan'] ?? '') == 'Belum Menikah' ? 'selected' : '' }}>
                                        Belum Menikah
                                    </option>
                                </select>

                                @error('status_pernikahan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="jabatan_id" class="col-sm-3 col-form-label">
                                Jabatan
                                <small class="text-danger">*</small>
                            </label>
                            <div class="col-sm-4">
                                <select name="jabatan_id" class="form-control @error('jabatan_id') is-invalid @enderror" id="jabatan_id">
                                    <option value="">- Pilih -</option>
                                    @foreach ($jabatan as $item)
                                        <option value="{{ $item['id'] }}"
                                            {{ old('jabatan_id', $edit['jabatan_id'] ?? '') == $item['id'] ? 'selected' : '' }}>
                                            {{ $item['jabatan'] }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('jabatan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="bank_id" class="col-sm-3 col-form-label">
                                Nama Bank
                            </label>
                            <div class="col-sm-4">
                                <select name="bank_id" class="form-control @error('bank_id') is-invalid @enderror" id="bank_id">
                                    <option value="">- Pilih -</option>
                                    @foreach ($bank as $edit)
                                        <option
                                            {{ old('bank_id', $edit['bank_id'] ?? '') == $edit['id'] ? 'selected' : '' }}
                                            value="{{ $edit['id'] }}">
                                            {{ $edit['nama_bank'] }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('bank_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="acc_no" class="col-sm-3 col-form-label">
                                Nomor Rekening
                            </label>
                            <div class="col-sm-3">
                                <input type="text" name="acc_no" class="form-control @error('acc_no') is-invalid @enderror" id="acc_no"
                                    placeholder="Masukkan Nomor Rekening"
                                    value="{{ old('acc_no', $edit['acc_no'] ?? '') }}">

                                @error('acc_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="acc_name" class="col-sm-3 col-form-label">
                                Nama Rekening
                            </label>
                            <div class="col-sm-5">
                                <input type="text" name="acc_name" class="form-control @error('acc_name') is-invalid @enderror" id="acc_name"
                                    placeholder="Masukkan Nama Rekening"
                                    value="{{ old('acc_name', $edit['acc_name'] ?? '') }}">

                                @error('acc_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="reset" class="btn btn-danger btn-sm">
                            <i class="fa fa-times"></i> Batalkan
                        </button>
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush
