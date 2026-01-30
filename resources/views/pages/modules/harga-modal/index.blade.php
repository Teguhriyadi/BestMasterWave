<table class="table table-bordered">
    <tbody>
        <tr>
            <td>SKU Barang</td>
            <td class="text-center">:</td>
            <td>
                {{ $barang['sku_barang'] }}
            </td>
        </tr>
        <tr>
            <td>Harga Modal</td>
            <td class="text-center">:</td>
            <td>
                <input required type="number" min="1" class="form-control" name="harga_modal" id="harga_modal" placeholder="0" value="{{ $barang['harga_modal'] }}">
            </td>
        </tr>
        <tr>
            <td>QTY</td>
            <td class="text-center">:</td>
            <td>
                <input required type="number" min="1" class="form-control" name="qty" id="qty" placeholder="0" value="{{ $jumlah }}">
            </td>
        </tr>
        <tr>
            <td>Harga Pembelian Terakhir</td>
            <td class="text-center">:</td>
            <td>
                <input required type="number" min="1" class="form-control" name="harga_pembelian_terakhir" id="harga_pembelian_terakhir" placeholder="0" value="{{ $barang['harga_pembelian_terakhir'] }}">
            </td>
        </tr>
        <tr>
            <td>Status SKU</td>
            <td class="text-center">:</td>
            <td>
                <select name="status_sku" class="form-control" id="status_sku" required>
                    <option value="">- Pilih -</option>
                    <option {{ $barang['status_sku'] == 'A' ? 'selected' : '' }} value="A">A</option>
                    <option {{ $barang['status_sku'] == 'N' ? 'selected' : '' }} value="N">N</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Tanggal Pembelian Terakhir</td>
            <td class="text-center">:</td>
            <td>
                <input required type="date" class="form-control" name="tanggal_pembelian_terakhir" id="tanggal_pembelian_terakhir" value="{{ $barang['tanggal_pembelian_terakhir'] }}">
            </td>
        </tr>
    </tbody>
</table>
