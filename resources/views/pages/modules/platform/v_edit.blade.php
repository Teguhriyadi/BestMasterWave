<form action="{{ url('/admin-panel/platform/' . $edit['id']) }}" method="POST">
    @csrf
    @method("PUT")
    <div class="modal-body">
        <div class="form-group">
            <label for="platform" class="form-label"> Nama Platform </label>
            <input type="text" class="form-control" name="platform" id="platform" placeholder="Masukkan Nama Platform" value="{{ old('platform', $edit['nama']) }}">
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
