<form action="{{ url('/admin-panel/jenis-peringatan/' . $edit['id']) }}" method="POST">
    @csrf
    @method("PUT")
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="kode" class="form-label">
                        Kode Peringatan
                        <small class="text-danger">*</small>
                    </label>
                    <input type="text" class="form-control @error('kode') is-invalid @enderror" name="kode"
                        id="kode" placeholder="Masukkan Kode Peringatan" value="{{ old('kode', $edit['kode']) }}">
                    @error('kode')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nama_peringatan" class="form-label">
                        Nama Peringatan
                        <small class="text-danger">*</small>
                    </label>
                    <input type="text" class="form-control @error('nama_peringatan') is-invalid @enderror"
                        name="nama_peringatan" id="nama_peringatan" placeholder="Masukkan Nama Jenis Denda"
                        value="{{ old('nama_peringatan', $edit['nama_peringatan']) }}">
                    @error('nama_peringatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="level" class="form-label">
                        Level Jenis Peringatan
                        <small class="text-danger">*</small>
                    </label>
                    <select name="level" class="form-control @error('level') is-invalid @enderror" id="level">
                        <option value="">- Pilih -</option>
                        <option {{ $edit['level'] == "1" ? 'selected' : '' }} value="1">1</option>
                        <option {{ $edit['level'] == "2" ? 'selected' : '' }} value="2">2</option>
                        <option {{ $edit['level'] == "3" ? 'selected' : '' }} value="3">3</option>
                    </select>
                    @error('level')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="masa_berlaku_hari" class="form-label">
                        Masa Berlaku Hari
                        <small class="text-danger">*</small>
                    </label>
                    <input type="number" class="form-control @error('masa_berlaku_hari') is-invalid @enderror"
                        name="masa_berlaku_hari" id="masa_berlaku_hari" placeholder="Masukkan Masa Berlaku Hari"
                        value="{{ old('masa_berlaku_hari', $edit['masa_berlaku_hari']) }}" min="0">
                    @error('masa_berlaku_hari')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="keterangan" class="form-label">
                Keterangan
                <small class="text-danger">*</small>
            </label>
            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" id="keterangan"
                rows="5" placeholder="Masukkan Keterangan">{{ old('keterangan', $edit['keterangan']) }}</textarea>
            @error('keterangan')
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
