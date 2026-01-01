<form action="{{ url('/admin-panel/bank/' . $edit['id']) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="form-group">
            <label for="nama_bank" class="form-label">
                Nama Bank
                <small class="text-danger">*</small>
            </label>
            <input type="text" class="form-control @error('nama_bank') is-invalid @enderror" name="nama_bank"
                id="nama_bank" placeholder="Masukkan Nama Bank" value="{{ old('nama_bank', $edit['nama_bank']) }}">
            @error('nama_bank')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="alias" class="form-label">
                Nama Alias Bank
                <small class="text-danger">*</small>
            </label>
            <input type="text" class="form-control @error('alias') is-invalid @enderror" name="alias"
                id="alias" placeholder="Masukkan Nama Alias Bank" value="{{ old('alias', $edit['alias']) }}">
            @error('alias')
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
