<form action="{{ url('/admin-panel/barang/' . $edit['id']) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="sku_barang" class="form-label"> SKU Barang </label>
                    <input type="text" class="form-control" name="sku_barang" id="sku_barang"
                        placeholder="Masukkan SKU Barang" value="{{ $edit['sku_barang'] }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="harga_modal" class="form-label"> Harga Modal </label>
                    <input type="number" min="1" class="form-control" name="harga_modal" id="harga_modal"
                        placeholder="0" value="{{ $edit['harga_modal'] }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="harga_pembelian_terakhir" class="form-label"> Harga Pembelian Terakhir </label>
                    <input type="number" class="form-control" name="harga_pembelian_terakhir" min="1"
                        placeholder="0" id="harga_pembelian_terakhir" value="{{ $edit['harga_pembelian_terakhir'] }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="tanggal_pembelian_terakhir" class="form-label"> Tanggal Pembelian Terakhir </label>
                    <input type="datetime-local" class="form-control" name="tanggal_pembelian_terakhir"
                        id="tanggal_pembelian_terakhir" value="{{ $edit['tanggal_pembelian_terakhir'] }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="seller_id" class="form-label">Nama Seller</label>
                    <select name="seller_id" class="form-control" id="seller_id">
                        <option value="">- Pilih -</option>
                        @foreach ($seller as $item)
                            <option value="{{ $item['id'] }}" {{ $edit['seller_id'] == $item['id'] ? 'selected' : '' }}>
                                {{ $item['nama'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="status_sku" class="form-label">Status SKU</label>
                    <select name="status_sku" class="form-control" id="status_sku">
                        <option value="">- Pilih -</option>
                        <option {{ $edit['status_sku'] == "A" ? 'selected' : '' }} value="A">Aktif</option>
                        <option {{ $edit['status_sku'] == "B" ? 'selected' : '' }} value="B">Tidak Aktif</option>
                    </select>
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
