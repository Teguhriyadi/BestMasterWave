<form action="{{ url('/admin-panel/jabatan/' . $edit['id']) }}" method="POST">
    @csrf
    @method("PUT")
    <div class="modal-body">
        <div class="form-group">
            <label for="nama_jabatan" class="form-label">
                Nama Jabatan
                <small class="text-danger">*</small>
            </label>
            <input type="text" class="form-control @error('nama_jabatan') is-invalid @enderror" name="nama_jabatan"
                id="nama_jabatan" placeholder="Masukkan Nama Seller" value="{{ old('nama_jabatan', $edit['nama_jabatan']) }}">
            @error('nama_jabatan')
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
