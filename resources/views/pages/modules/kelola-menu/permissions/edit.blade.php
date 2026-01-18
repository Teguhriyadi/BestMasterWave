<form action="{{ url('/admin-panel/permissions/' . $edit['id']) }}" method="POST">
    @csrf
    @method("PUT")
    <div class="modal-body">
        <div class="form-group">
            <label for="nama" class="form-label">
                Nama Modul
                <small class="text-danger">*</small>
            </label>
            <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" id="nama"
                placeholder="Masukkan Nama Modul" value="{{ old('nama', $edit['nama']) }}">
            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="akses" class="form-label">
                        Akses <small>(ex : platform)</small>
                        <small class="text-danger">*</small>
                    </label>
                    <input type="text" class="form-control @error('akses') is-invalid @enderror" name="akses"
                        id="akses" placeholder="Masukkan Akses" value="{{ old('akses', $akses_nama) }}">
                    @error('akses')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="akses" class="form-label">
                        Tipe Akses
                        <small class="text-danger">*</small>
                    </label>
                    <select name="tipe_akses" class="form-control @error('tipe_akses') is-invalid @enderror"
                        id="tipe_akses">
                        <option value="">- Pilih Tipe -</option>
                        <option {{ old('tipe_akses', $tipe_akses) == 'read' ? 'selected' : '' }} value="read">Read (Baca)</option>
                        <option {{ old('tipe_akses', $tipe_akses) == 'create' ? 'selected' : '' }} value="create">Create (Tambah)
                        </option>
                        <option {{ old('tipe_akses', $tipe_akses) == 'edit' ? 'selected' : '' }} value="edit">Update (Ubah)
                        </option>
                        <option {{ old('tipe_akses', $tipe_akses) == 'delete' ? 'selected' : '' }} value="delete">Delete (Hapus)
                        </option>
                        <option {{ old('tipe_akses', $tipe_akses) == 'show' ? 'selected' : '' }} value="show">Show (Detail)
                        </option>
                        <option {{ old('tipe_akses', $tipe_akses) == 'change_status' ? 'selected' : '' }} value="change_status">
                            Change Status (Ubah Status)</option>
                    </select>
                    @error('tipe_akses')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="menu_id" class="form-label">
                Nama Menu
                <small class="text-danger">*</small>
            </label>
            <select name="menu_id" class="form-control @error('menu_id') is-invalid @enderror" id="menu_id">
                <option value="">- Pilih -</option>
                @foreach ($menu as $item)
                    <option value="{{ $item['id'] }}" {{ $edit['menu_id'] == $item['id'] ? 'selected' : '' }}>
                        {{ $item['nama_menu'] }}
                    </option>
                @endforeach
            </select>

            @error('menu_id')
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
