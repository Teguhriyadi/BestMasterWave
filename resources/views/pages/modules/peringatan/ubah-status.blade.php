<form action="{{ url('/admin-panel/peringatan/' . $edit['id'] . '/ubah-status') }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="form-group">
            <label for="nama_karyawan"> Nama Karyawan </label>
            <input type="text" class="form-control" value="{{ $edit['karyawan']['nama'] }}" disabled>
        </div>
        <div class="form-group">
            <label for="nama_karyawan"> Jenis Peringatan </label>
            <input type="text" class="form-control" value="{{ $edit['jenis_peringatan']['nama_peringatan'] }}"
                disabled>
        </div>
        <div class="form-group">
            <label for="status"> Status </label>
            <select name="status" class="form-control @error('status') is-invalid @enderror" id="status">
                <option value="">- Pilih -</option>
                <option value="Aktif">Aktif</option>
                <option value="Expired">Expired</option>
                <option value="Dicabut">Dicabut</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-secondary btn-sm" data-dismiss="modal">
            <i class="fa fa-times"></i> Batalkan
        </button>
        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Yakin ? Ingin Mengubah Status Data Ini?')">
            <i class="fa fa-edit"></i> Konfirmasi
        </button>
    </div>
</form>
