<form action="{{ url('/admin-panel/absensi/' . $edit['id']) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="form-group">
            <label for="nama_lokasi" class="form-label">
                Nama Karyawan
                <small class="text-danger">*</small>
            </label>
            <input type="text" class="form-control" value="{{ $edit['nama_karyawan'] }}" disabled>
        </div>
        <div class="form-group">
            <label for="nama_lokasi" class="form-label">
                Status Absen
                <small class="text-danger">*</small>
            </label>
            <input type="text" class="form-control" value="{{ $edit['status'] }}" disabled>
        </div>
        <div class="form-group">
            <label for="tanggal_waktu" class="form-label">
                Tanggal Absensi
                <small class="text-danger">*</small>
            </label>
            <input type="datetime-local" class="form-control @error('tanggal_waktu') is-invalid @enderror" name="tanggal_waktu"
                id="tanggal_waktu" placeholder="Masukkan Nama Lokasi" value="{{ old('tanggal_waktu', $edit['tanggal_waktu']) }}">
            @error('tanggal_waktu')
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
