<form action="{{ url('/admin-panel/seller/' . $edit['id']) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="form-group">
            <label for="platform_id" class="form-label"> Nama Platform </label>
            <select name="platform_id" class="form-control" id="platform_id">
                <option value="">- Pilih -</option>
                @foreach ($platform as $item)
                    <option value="{{ $item->id }}" {{ $edit['platform_id'] == $item['id'] ? 'selected' : '' }}>
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="nama" class="form-label"> Nama Seller </label>
            <input type="text" class="form-control" name="nama" id="nama" placeholder="Masukkan Nama Seller" value="{{ $edit['nama'] }}">
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
