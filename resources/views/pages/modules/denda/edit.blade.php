<form action="{{ url('/admin-panel/denda/' . $edit['id']) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="form-group">
            <label for="karyawan_id">
                Nama Karyawan
                <small class="text-danger">*</small>
            </label>
            <select name="karyawan_id" class="form-control select2" id="karyawan_id">
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
        <div class="form-group">
            <label for="jenis_denda_id">
                Jenis Denda
                <small class="text-danger">*</small>
            </label>
            <select name="jenis_denda_id" class="form-control select2" id="jenis_denda_id">
                <option value="">- Pilih -</option>
                @foreach ($denda as $item)
                    <option value="{{ $item['id'] }}"
                        {{ (string) old('jenis_denda_id', optional($edit)->jenis_denda_id) === (string) $item['id'] ? 'selected' : '' }}>
                        {{ $item['kode'] }} - {{ $item['nama_jenis'] }}
                    </option>
                @endforeach
            </select>
            @error('jenis_denda_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="periode_gaji">
                        Periode Potongan Gaji
                        <small class="text-danger">*</small>
                    </label>
                    <input type="date" name="periode_gaji"
                        class="form-control @error('periode_gaji') is-invalid @enderror"
                        value="{{ old('periode_gaji', optional($edit)->periode_gaji?->format('Y-m-d')) }}">
                    @error('periode_gaji')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="tanggal_denda">
                        Tanggal Denda
                        <small class="text-danger">*</small>
                    </label>
                    <input type="date" name="tanggal_denda"
                        class="form-control @error('tanggal_denda') is-invalid @enderror"
                        value="{{ old('tanggal_denda', optional($edit)->tanggal_denda?->format('Y-m-d')) }}">
                    @error('tanggal_denda')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="periode_gaji">
                Keterangan
                <small class="text-danger">*</small>
            </label>
            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" rows="5" placeholder="Masukkan Ketetangan">{{ old('keterangan', $edit['keterangan']) }}</textarea>
            @error('keterangan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="card-footer">
        <button type="reset" class="btn btn-secondary btn-sm">
            <i class="fa fa-times"></i> Batalkan
        </button>
        <button type="submit" class="btn btn-success btn-sm">
            <i class="fa fa-save"></i> Simpan
        </button>
    </div>
</form>
