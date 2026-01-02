<form action="{{ url('/admin-panel/divisi/' . $edit['id']) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="form-group">
            <label for="nama_divisi" class="form-label">
                Nama Divisi
                <small class="text-danger">*</small>
            </label>
            <input type="text" class="form-control" name="nama_divisi" id="nama_divisi"
                placeholder="Masukkan Nama Divisi" value="{{ old('nama_divisi', $edit['nama_divisi']) }}">
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
