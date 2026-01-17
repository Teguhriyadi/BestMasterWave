<form action="{{ url('/admin-panel/jenis-denda/' . $edit['id']) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="form-group">
            <label for="kode" class="form-label">
                Kode Denda
                <small class="text-danger">*</small>
            </label>
            <input type="text" class="form-control @error('kode') is-invalid @enderror" name="kode" id="kode"
                placeholder="Masukkan Kode Denda" value="{{ old('kode', $edit['kode']) }}">
            @error('kode')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nama_jenis" class="form-label">
                        Nama Jenis Denda
                        <small class="text-danger">*</small>
                    </label>
                    <input type="text" class="form-control @error('nama_jenis') is-invalid @enderror"
                        name="nama_jenis" id="nama_jenis" placeholder="Masukkan Nama Jenis Denda"
                        value="{{ old('nama_jenis', $edit['nama_jenis']) }}">
                    @error('nama_jenis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nominal" class="form-label">
                        Nominal Potongan
                        <small class="text-danger">*</small>
                    </label>
                    <input type="number" class="form-control @error('nominal') is-invalid @enderror" name="nominal"
                        id="nominal" placeholder="Masukkan Nominal Potongan" value="{{ old('nominal', $edit['nominal']) }}"
                        min="0">
                    @error('nominal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="keterangan" class="form-label">
                Keterangan
                <small class="text-danger">*</small>
            </label>
            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" id="keterangan"
                rows="5" placeholder="Masukkan Keterangan">{{ old('keterangan', $edit['keterangan']) }}</textarea>
            @error('keterangan')
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
