<form action="{{ url('/admin-panel/supplier/' . $edit['id']) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nama_supplier" class="form-label">
                        Nama Supplier
                        <small class="text-danger">*</small>
                    </label>
                    <input type="text" class="form-control @error('nama_supplier') is-invalid @enderror"
                        name="nama_supplier" id="nama_supplier" placeholder="Masukkan Nama Supplier"
                        value="{{ old('nama_supplier', $edit['nama_supplier']) }}">
                    @error('nama_supplier')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="no_npwp" class="form-label">
                        No. NPWP
                    </label>
                    <input type="text" class="form-control @error('no_npwp') is-invalid @enderror" name="no_npwp"
                        id="no_npwp" placeholder="Masukkan No. NPWP" value="{{ old('no_npwp', $edit['no_npwp']) }}">
                    @error('no_npwp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="kontak_hubungi" class="form-label">
                        Kontak Person
                        <small class="text-danger">*</small>
                    </label>
                    <input type="text" class="form-control @error('kontak_hubungi') is-invalid @enderror"
                        name="kontak_hubungi" id="kontak_hubungi" placeholder="Masukkan Kontak Person"
                        value="{{ old('kontak_hubungi', $edit['kontak_hubungi']) }}">
                    @error('kontak_hubungi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nomor_kontak" class="form-label">
                        Kontak Yang Bisa Dihubungi
                        <small class="text-danger">*</small>
                    </label>
                    <input type="text" class="form-control @error('nomor_kontak') is-invalid @enderror"
                        name="nomor_kontak" id="nomor_kontak" placeholder="Contoh : 081214711741 / ex@gmail.com"
                        value="{{ old('nomor_kontak', $edit['nomor_kontak']) }}">
                    @error('nomor_kontak')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="no_rekening" class="form-label">
                        No. Rekening
                        <small class="text-danger">*</small>
                    </label>
                    <input type="text" class="form-control @error('no_rekening') is-invalid @enderror"
                        name="no_rekening" id="no_rekening" placeholder="Masukkan No. Rekening"
                        value="{{ old('no_rekening', $edit['no_rekening']) }}">
                    @error('no_rekening')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="nama_rekening" class="form-label">
                        Nama Rekening
                        <small class="text-danger">*</small>
                    </label>
                    <input type="text" class="form-control @error('nama_rekening') is-invalid @enderror"
                        name="nama_rekening" id="nama_rekening" placeholder="Masukkan Nama Rekening"
                        value="{{ old('nama_rekening', $edit['nama_rekening']) }}">
                    @error('nama_rekening')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="bank_id" class="form-label">
                        Nama Bank
                        <small class="text-danger">*</small>
                    </label>
                    <select name="bank_id" class="form-control @error('bank_id') is-invalid @enderror" id="bank_id">
                        <option value="">- Pilih -</option>
                        @foreach ($bank as $item)
                            <option value="{{ $item['id'] }}" @selected(old('bank_id', $edit['bank_id'] ?? '') == $item['id'])>
                                {{ $item['alias'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('bank_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ketentuan_tempo_pembayaran" class="form-label">
                        Ketentuan Tempo Pembayaran
                        <small class="text-danger">*</small>
                    </label>
                    <input type="text" class="form-control @error('ketentuan_tempo_pembayaran') is-invalid @enderror"
                        name="ketentuan_tempo_pembayaran" id="ketentuan_tempo_pembayaran"
                        placeholder="Masukkan Tempo Pembayaran"
                        value="{{ old('ketentuan_tempo_pembayaran', $edit['ketentuan_tempo_pembayaran']) }}">
                    @error('ketentuan_tempo_pembayaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="rate_ppn" class="form-label">
                        Rate PPN
                    </label>
                    <input type="number" class="form-control @error('rate_ppn') is-invalid @enderror"
                        name="rate_ppn" id="rate_ppn" placeholder="0" min="0" max="100"
                        value="{{ old('rate_ppn', $edit['rate_ppn']) }}">
                    @error('rate_ppn')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="pkp" class="form-label">
                PKP
                <small class="text-danger">*</small>
            </label>
            <select name="pkp" class="form-control @error('pkp') is-invalid @enderror" id="pkp">
                <option value="">- Pilih -</option>
                <option value="PKP" @selected(old('pkp', $edit['pkp'] ?? '') === 'PKP')>PKP</option>
                <option value="Non PKP" @selected(old('pkp', $edit['pkp'] ?? '') === 'Non PKP')>Non PKP</option>
            </select>
            @error('pkp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="alamat" class="form-label">
                Alamat
                <small class="text-danger">*</small>
            </label>
            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" id="alamat" rows="5"
                placeholder="Masukkan Alamat">{{ old('alamat', $edit['alamat']) }}</textarea>
            @error('alamat')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-secondary btn-sm" data-dismiss="modal">
            <i class="fa fa-times"></i> Batalkan
        </button>
        <button type="submit" class="btn btn-success btn-sm">
            <i class="fa fa-save"></i> Simpan
        </button>
    </div>
</form>
