<form action="{{ url('/admin-panel/permissions/' . $edit['id']) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="modal-body">

        <div class="form-group">
            <label>Nama Modul <small class="text-danger">*</small></label>
            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                value="{{ old('nama', $edit['nama']) }}">
            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Akses <small class="text-danger">*</small></label>
                    <input type="text" name="akses" class="form-control @error('akses') is-invalid @enderror"
                        value="{{ old('akses', $akses_nama) }}">
                    @error('akses')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Nama Menu <small class="text-danger">*</small></label>
                    <select name="menu_id" class="form-control @error('menu_id') is-invalid @enderror"
                        id="menu_id_edit">

                        <option value="">- Pilih -</option>
                        @foreach ($menu as $group)
                            <optgroup label="{{ strtoupper($group['label']) }}">
                                @foreach ($group['items'] as $item)
                                    <option {{ old('menu_id', $edit['menu_id']) == $item['id'] ? 'selected' : '' }} value="{{ $item['id'] }}" data-header="{{ $group['label'] }}"
                                        data-type="{{ $item['type'] }}">
                                        {{ $item['text'] }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>

                    @error('menu_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Tipe Akses <small class="text-danger">*</small></label>

            @php
                $oldTipe = old('tipe_akses', $tipe_akses ?? []);
            @endphp


            <select name="tipe_akses[]" id="tipe_akses_edit"
                class="form-control @error('tipe_akses') is-invalid @enderror" multiple>

                <option value="read" {{ in_array('read', $oldTipe) ? 'selected' : '' }}>Read</option>
                <option value="create" {{ in_array('create', $oldTipe) ? 'selected' : '' }}>Create</option>
                <option value="edit" {{ in_array('edit', $oldTipe) ? 'selected' : '' }}>Edit</option>
                <option value="delete" {{ in_array('delete', $oldTipe) ? 'selected' : '' }}>Delete</option>
                <option value="show" {{ in_array('show', $oldTipe) ? 'selected' : '' }}>Show</option>
                <option value="change_status" {{ in_array('change_status', $oldTipe) ? 'selected' : '' }}>
                    Change Status
                </option>
            </select>

            @error('tipe_akses')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="modal-footer">
        <button type="reset" class="btn btn-secondary btn-sm" data-dismiss="modal">
            <i class="fa fa-times"></i> Batal
        </button>
        <button type="submit" class="btn btn-success btn-sm">
            <i class="fa fa-save"></i> Simpan
        </button>
    </div>

</form>
