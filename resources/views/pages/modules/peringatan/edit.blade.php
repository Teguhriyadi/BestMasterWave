<form action="{{ url('/admin-panel/peringatan/' . $edit['id']) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="karyawan_id">
                        Nama Karyawan
                        <small class="text-danger">*</small>
                    </label>
                    <select name="karyawan_id" class="form-control @error('karyawan_id') is-invalid @enderror"
                        id="karyawan_id">
                        <option value="">- Pilih -</option>
                        @foreach ($karyawan as $item)
                            <option value="{{ $item['id'] }}"
                                {{ (string) old('karyawan_id', optional($edit)->karyawan_id) === (string) $item['id'] ? 'selected' : '' }}>
                                {{ $item['nama'] }} - {{ $item['jabatan'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('karyawan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="jenis_peringatan_id">
                        Jenis Peringatan
                        <small class="text-danger">*</small>
                    </label>
                    <select name="jenis_peringatan_id"
                        class="form-control @error('jenis_peringatan_id') is-invalid @enderror"
                        id="jenis_peringatan_id">

                        <option value="">- Pilih -</option>

                        @foreach ($jenis_peringatan as $item)
                            <option value="{{ $item['id'] }}"
                                {{ (string) old('jenis_peringatan_id', optional($edit)->jenis_peringatan_id) === (string) $item['id'] ? 'selected' : '' }}>
                                {{ $item['kode'] }} - {{ $item['nama_peringatan'] }} - {{ $item['level'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_peringatan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="tanggal_pelanggaran" class="form-label">
                        Tanggal Pelanggaran
                        <small class="text-danger">*</small>
                    </label>
                    <input type="date" class="form-control @error('tanggal_pelanggaran') is-invalid @enderror"
                        name="tanggal_pelanggaran" id="tanggal_pelanggaran"
                        value="{{ old('tanggal_pelanggaran', optional($edit)->tanggal_pelanggaran?->format('Y-m-d')) }}">
                    @error('tanggal_pelanggaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="tanggal_terbit_sp" class="form-label">
                        Tanggal Terbit SP
                        <small class="text-danger">*</small>
                    </label>
                    <input type="date" class="form-control @error('tanggal_terbit_sp') is-invalid @enderror"
                        name="tanggal_terbit_sp" id="tanggal_terbit_sp"
                        value="{{ old('tanggal_terbit_sp', optional($edit)->tanggal_terbit_sp?->format('Y-m-d')) }}">
                    @error('tanggal_terbit_sp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="berlaku_sampai" class="form-label">
                        Berlaku Sampai
                        <small class="text-danger">*</small>
                    </label>
                    <input type="date" class="form-control @error('berlaku_sampai') is-invalid @enderror"
                        name="berlaku_sampai" id="berlaku_sampai"
                        value="{{ old('berlaku_sampai', optional($edit)->berlaku_sampai?->format('Y-m-d')) }}">
                    @error('berlaku_sampai')
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
