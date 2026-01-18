<form action="{{ url('/admin-panel/setup-jam-kerja/' . $edit['id']) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="jam_masuk" class="form-label">
                        Jam Masuk
                        <small class="text-danger">*</small>
                    </label>
                    <input type="time" class="form-control @error('jam_masuk') is-invalid @enderror" name="jam_masuk"
                        id="jam_masuk" value="{{ old('jam_masuk', $edit['jam_masuk'] ? date('H:i', strtotime($edit['jam_masuk'])) : '') }}">
                    @error('jam_masuk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="jam_pulang" class="form-label">
                        Jam Pulang
                        <small class="text-danger">*</small>
                    </label>
                    <input type="time" class="form-control @error('jam_pulang') is-invalid @enderror"
                        name="jam_pulang" id="jam_pulang" value="{{ old('jam_pulang', $edit['jam_pulang'] ? date('H:i', strtotime($edit['jam_pulang'])) : '') }}">
                    @error('jam_pulang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="toleransi_menit" class="form-label">
                        Toleransi Keterlambatan
                        <small class="text-danger">*</small>
                    </label>
                    <input type="number" class="form-control @error('toleransi_menit') is-invalid @enderror"
                        name="toleransi_menit" id="toleransi_menit" min="0" placeholder="0"
                        value="{{ old('toleransi_menit', $edit['toleransi_menit']) }}">
                    @error('toleransi_menit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="divisi_id" class="form-label">
                        Divisi
                        <small class="text-danger">*</small>
                    </label>
                    <select name="divisi_id" class="form-control @error('divisi_id') is-invalid @enderror"
                        id="divisi_id">
                        <option value="">- Pilih -</option>
                        @foreach ($divisi as $item)
                            <option {{ old('divisi_id', $edit['divisi_id']) == $item['id'] ? 'selected' : '' }} value="{{ $item['id'] }}">
                                {{ $item['nama_divisi'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('divisi_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
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
