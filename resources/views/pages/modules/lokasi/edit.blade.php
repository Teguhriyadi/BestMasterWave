<form action="{{ url('/admin-panel/lokasi/' . $edit['id']) }}" method="POST">
    @csrf
    @method("PUT")
    <div class="modal-body">
        <div class="form-group">
            <label for="kode_lokasi" class="form-label">
                Kode Lokasi
                <small class="text-danger">*</small>
            </label>
            <input type="text" class="form-control @error('kode_lokasi') is-invalid @enderror" name="kode_lokasi"
                id="kode_lokasi" placeholder="Masukkan Nama Lokasi" value="{{ old('kode_lokasi', $edit['kode_lokasi']) }}">
            @error('kode_lokasi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="nama_lokasi" class="form-label">
                Nama Lokasi
                <small class="text-danger">*</small>
            </label>
            <input type="text" class="form-control @error('nama_lokasi') is-invalid @enderror" name="nama_lokasi"
                id="nama_lokasi" placeholder="Masukkan Nama Lokasi" value="{{ old('nama_lokasi', $edit['nama_lokasi']) }}">
            @error('nama_lokasi')
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
