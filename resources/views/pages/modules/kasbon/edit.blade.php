<form action="{{ url('/admin-panel/kasbon/' . $edit['id']) }}" method="POST">
    @csrf
    @method("PUT")
    <div class="modal-body">
        <div class="form-group">
            <label for="karyawan_id" class="form-label">
                Nama Karyawan
                <small class="text-danger">*</small>
            </label>
            <input type="text" class="form-control" value="{{ $edit['karyawan']['nama'] }}" readonly>
            @error('karyawan_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="jumlah" class="form-label">
                        Jumlah
                        <small class="text-danger">*</small>
                    </label>
                    <input type="number" class="form-control @error('jumlah') is-invalid @enderror" name="jumlah"
                        id="jumlah" placeholder="0" min="1" value="{{ old('jumlah', number_format($edit['jumlah_awal'], 0, ',', '.')) }}" readonly>
                    @error('jumlah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="sisa" class="form-label">
                        Sisa
                        <small class="text-danger">*</small>
                    </label>
                    <input type="number" class="form-control @error('sisa') is-invalid @enderror" name="sisa"
                        id="sisa" placeholder="0" min="1" value="{{ old('sisa', number_format($edit['sisa'], 0, ',', '.')) }}" readonly>
                    @error('sisa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="tanggal_mulai" class="form-label">
                        Tanggal Mulai
                        <small class="text-danger">*</small>
                    </label>
                    <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                        name="tanggal_mulai" id="tanggal_mulai" placeholder="0" min="1"
                        value="{{ old('tanggal_mulai', \Carbon\Carbon::parse($edit['tanggal_mulai'])->format('Y-m-d')) }}" readonly>
                    @error('tanggal_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="jumlah" class="form-label">
                Keterangan
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
