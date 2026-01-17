<form action="{{ url('/admin-panel/denda/' . $edit['id'] . '/ubah-status') }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="form-group">
            <label for="nama_karyawan"> Nama Karyawan </label>
            <input type="text" class="form-control" id="nama_karyawan" value="{{ $edit['karyawan']['nama'] }}" disabled>
        </div>
        <div class="form-group">
            <label for="jenis_denda"> Jenis Denda </label>
            <input type="text" class="form-control" id="jenis_denda" value="{{ $edit["jenis_denda"]["nama_jenis"] }}" disabled>
        </div>
        <div class="form-group">
            <label for="status"> Status </label>
            <select name="status" class="form-control @error('status') is-invalid @enderror" id="status">
                <option value="">- Pilih -</option>
                <option value="Disetujui">Disetujui</option>
                <option value="Dipotong">Dibatalkan</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="card-footer">
        <button type="reset" class="btn btn-secondary btn-sm">
            <i class="fa fa-times"></i> Batalkan
        </button>
        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Yakin ? Ingin Mengubah Status Ini?')">
            <i class="fa fa-edit"></i> Konfirmasi
        </button>
    </div>
</form>
