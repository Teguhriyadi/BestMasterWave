<form action="{{ url('/admin-panel/ketidakhadiran/' . $edit['id']) }}" method="POST">
    @csrf
    @method("PUT")
    <div class="modal-body">
        <div class="form-group">
            <label for="karyawan_id" class="form-label">
                Nama Karyawan
                <small class="text-danger">*</small>
            </label>
            <select name="karyawan_id" class="form-control @error('karyawan_id') is-invalid @enderror" id="karyawan_id">
                <option value="">- Pilih -</option>
                @foreach ($karyawan as $item)
                    <option value="{{ $item['id'] }}" {{ $item['id'] == $edit['karyawan_id'] ? 'selected' : '' }}>
                        {{ $item['nama'] }} - {{ $item['jabatan'] }}
                    </option>
                @endforeach
            </select>
            @error('karyawan_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="status" class="form-label">
                        Status
                        <small class="text-danger">*</small>
                    </label>
                    <select name="status" class="form-control @error('status') is-invalid @enderror" id="status">
                        <option value="">- Pilih -</option>
                        <option {{ $edit['status'] == "Alfa" ? 'selected' : '' }} value="Alfa">Alfa</option>
                        <option {{ $edit['status'] == "Sakit" ? 'selected' : '' }} value="Sakit">Sakit</option>
                        <option {{ $edit['status'] == "Izin" ? 'selected' : '' }} value="Izin">Izin</option>
                        <option {{ $edit['status'] == "Cuti" ? 'selected' : '' }} value="Cuti">Cuti</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="tanggal" class="form-label">
                        Tanggal
                        <small class="text-danger">*</small>
                    </label>
                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal"
                        id="tanggal" value="{{ old('tanggal', $edit['tanggal']) }}">
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="alasan" class="form-label">
                Alasan
            </label>
            <textarea name="alasan" class="form-control @error('alasan') is-invalid @enderror" id="alasan" rows="5"
                placeholder="Masukkan Alasan">{{ old('alasan', $edit['alasan']) }}</textarea>
            @error('alasan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="foto" class="form-label">
                Foto
            </label>
            <input type="file" class="form-control @error('foto') is-invalid @enderror" name="foto"
                id="foto">
            @error('foto')
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
