<form action="{{ url('/admin-panel/role/' . $edit['id']) }}" method="POST">
    @csrf
    @method("PUT")
    <div class="modal-body">
        <div class="form-group">
            <label for="nama_role" class="form-label">
                Nama Role
                <small class="text-danger">*</small>
            </label>
            <input type="text" class="form-control @error('nama_role') is-invalid @enderror" name="nama_role"
                id="nama_role" placeholder="Masukkan Nama Seller" value="{{ old('nama_role', $edit['nama_role']) }}">
            @error('nama_role')
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
