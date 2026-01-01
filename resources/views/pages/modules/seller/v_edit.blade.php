<form action="{{ url('/admin-panel/seller/' . $edit['id']) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="form-group">
            <label for="platform_id" class="form-label">
                Nama Platform
                <small class="text-danger">*</small>
            </label>
            <select name="platform_id" class="form-control" id="platform_id">
                <option value="">- Pilih -</option>
                @foreach ($platform as $item)
                    <option value="{{ $item->id }}" {{ old('platform_id', $edit->platform_id ?? '') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>
            @error('platform_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="nama" class="form-label">
                Nama Seller
                <small class="text-danger">*</small>
            </label>
            <input type="text" class="form-control" name="nama" id="nama" placeholder="Masukkan Nama Seller"
                value="{{ old('nama', $edit['nama']) }}">
            @error('nama')
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
