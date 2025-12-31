<form action="{{ url('/admin-panel/supplier') }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nama_supplier" class="form-label"> Nama Supplier </label>
                    <input type="text" class="form-control" name="nama_supplier" id="nama_supplier"
                        placeholder="Masukkan Nama Supplier">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="no_npwp" class="form-label"> No. NPWP </label>
                    <input type="text" class="form-control" name="no_npwp" id="no_npwp"
                        placeholder="Masukkan No. NPWP">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="kontak_hubungi" class="form-label"> Jenis Kontak Yang Dihubungi </label>
                    <select name="kontak_hubungi" class="form-control" id="kontak_hubungi">
                        <option value="">- Pilih -</option>
                        <option value="WA_HP">WhatsApp + Nomor Handphone</option>
                        <option value="WA">WhatApp</option>
                        <option value="NO_HP">Nomor Handphone</option>
                        <option value="GMAIL">Email</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nomor_kontak" class="form-label"> Kontak Yang Bisa Dihubungi </label>
                    <input type="text" class="form-control" name="nomor_kontak" id="nomor_kontak"
                        placeholder="Contoh : 081214711741 / ex@gmail.com">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="no_rekening" class="form-label">No. Rekening</label>
                    <input type="text" class="form-control" name="no_rekening" id="no_rekening"
                        placeholder="Masukkan No. Rekening">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="nama_rekening" class="form-label">Nama Rekening</label>
                    <input type="text" class="form-control" name="nama_rekening" id="nama_rekening"
                        placeholder="Masukkan Nama Rekening">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="bank_id" class="form-label">Nama Bank</label>
                    <select name="bank_id" class="form-control" id="bank_id">
                        <option value="">- Pilih -</option>
                        @foreach ($bank as $item)
                            <option value="{{ $item['id'] }}">
                                {{ $item['alias'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ketentuan_tempo_pembayaran" class="form-label">Ketentuan Tempo
                        Pembayaran</label>
                    <input type="text" class="form-control" name="ketentuan_tempo_pembayaran"
                        id="ketentuan_tempo_pembayaran" placeholder="Masukkan Tempo Pembayaran">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="rate_ppn" class="form-label">Rate PPN</label>
                    <input type="number" class="form-control" name="rate_ppn" id="rate_ppn" placeholder="0"
                        min="1">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="pkp" class="form-label">PKP</label>
            <select name="pkp" class="form-control" id="pkp">
                <option value="">- Pilih -</option>
                <option {{ $edit['pkp'] == "PKP" ? 'selected' : '' }} value="PKP">PKP</option>
                <option {{ $edit['pkp'] == "Non PKP" ? 'selected' : '' }} value="Non PKP">Non PKP</option>
            </select>
        </div>
        <div class="form-group">
            <label for="alamat" class="form-label"> Alamat </label>
            <textarea name="alamat" class="form-control" id="alamat" rows="5" placeholder="Masukkan Alamat"></textarea>
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
