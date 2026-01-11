<form action="{{ url('/admin-panel/menu/' . $edit['id']) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="form-group">
            <label>Nama Menu <small class="text-danger">*</small></label>
            <input type="text"
                name="nama_menu"
                class="form-control @error('nama_menu') is-invalid @enderror"
                value="{{ old('nama_menu', $edit['nama_menu']) }}">
            @error('nama_menu')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label>Tipe Menu <small class="text-danger">*</small></label>
            <select name="tipe_menu"
                class="form-control tipe-menu @error('tipe_menu') is-invalid @enderror">
                <option value="">- Pilih -</option>
                <option value="header" {{ $edit['type']=='header' ? 'selected' : '' }}>Header</option>
                <option value="menu" {{ $edit['type']=='menu' ? 'selected' : '' }}>Menu</option>
                <option value="submenu" {{ $edit['type']=='submenu' ? 'selected' : '' }}>Sub Menu</option>
            </select>
            @error('tipe_menu')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="parent-wrap" style="display:none">
            <div class="form-group">
                <label>Nama Parent Menu <small class="text-danger">*</small></label>
                <select name="parent_id"
                    class="form-control @error('parent_id') is-invalid @enderror">
                    <option value="">- Pilih Parent -</option>
                    @foreach ($parents as $parent)
                        <option value="{{ $parent['id'] }}"
                            {{ old('parent_id', $edit['parent_id']) == $parent['id'] ? 'selected' : '' }}>
                            {{ $parent['nama_menu'] }}
                        </option>
                    @endforeach
                </select>
                @error('parent_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-group url-wrap">
            <label>URL Menu <small class="text-danger">*</small></label>
            <input type="text"
                name="url"
                class="form-control @error('url') is-invalid @enderror"
                value="{{ old('url', $edit['url_menu']) }}">
            @error('url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label>Ikon Menu</label>
            <input type="text"
                name="icon"
                class="form-control @error('icon') is-invalid @enderror"
                value="{{ old('icon', $edit['icon']) }}">
            @error('icon')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
            <i class="fa fa-times"></i> Batal
        </button>
        <button type="submit" class="btn btn-success btn-sm">
            <i class="fa fa-save"></i> Simpan
        </button>
    </div>
</form>
