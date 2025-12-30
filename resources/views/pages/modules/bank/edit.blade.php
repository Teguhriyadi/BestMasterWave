<form action="{{ url('/admin-panel/supplier/' . $edit['id']) }}" method="POST">
    @csrf
    @method("PUT")
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nama_supplier" class="form-label"> Nama Supplier </label>
                    <input type="text" class="form-control" name="nama_supplier" id="nama_supplier"
                        placeholder="Masukkan Nama Supplier" value="{{ $edit['nama_supplier'] }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="no_npwp" class="form-label"> No. NPWP </label>
                    <input type="text" class="form-control" name="no_npwp" id="no_npwp"
                        placeholder="Masukkan No. NPWP" value="{{ $edit['no_npwp'] }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="kontak_hubungi" class="form-label"> Jenis Kontak Yang Dihubungi </label>
                    <select name="kontak_hubungi" class="form-control" id="kontak_hubungi">
                        <option value="">- Pilih -</option>
                        <option {{ $edit['kontak_hubungi'] == "WA_HP" ? 'selected' : '' }} value="WA_HP">WhatsApp + Nomor Handphone</option>
                        <option {{ $edit['kontak_hubungi'] == "SMS" ? 'selected' : '' }} value="SMS">SMS</option>
                        <option {{ $edit['kontak_hubungi'] == "WA" ? 'selected' : '' }} value="WA">WhatApp</option>
                        <option {{ $edit['kontak_hubungi'] == "NO_HP" ? 'selected' : '' }} value="NO_HP">Nomor Handphone</option>
                        <option {{ $edit['kontak_hubungi'] == "GMAIL" ? 'selected' : '' }} value="GMAIL">Email</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nomor_kontak" class="form-label"> Kontak Yang Bisa Dihubungi </label>
                    <input type="text" class="form-control" name="nomor_kontak" id="nomor_kontak"
                        placeholder="Contoh : 081214711741 / ex@gmail.com" value="{{ $edit['nomor_kontak'] }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="no_rekening" class="form-label">No. Rekening</label>
                    <input type="text" class="form-control" name="no_rekening" id="no_rekening"
                        placeholder="Masukkan No. Rekening" value="{{ $edit['no_rekening'] }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="nama_rekening" class="form-label">Nama Rekening</label>
                    <input type="text" class="form-control" name="nama_rekening" id="nama_rekening"
                        placeholder="Masukkan Nama Rekening" value="{{ $edit['nama_rekening'] }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="bank" class="form-label">Nama Bank</label>
                    <input type="text" class="form-control" name="bank" id="bank"
                        placeholder="Masukkan Nama Bank" value="{{ $edit['bank'] }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ketentuan_tempo_pembayaran" class="form-label">Ketentuan Tempo
                        Pembayaran</label>
                    <input type="text" class="form-control" name="ketentuan_tempo_pembayaran"
                        id="ketentuan_tempo_pembayaran" placeholder="Masukkan Tempo Pembayaran" value="{{ $edit['ketentuan_tempo_pembayaran'] }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="rate_ppn" class="form-label">Rate PPN</label>
                    <input type="number" class="form-control" name="rate_ppn" id="rate_ppn" placeholder="0"
                        min="1" value="{{ $edit['rate_ppn'] }}">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="pkp" class="form-label">PKP</label>
            <input type="text" class="form-control" name="pkp" id="pkp" placeholder="Masukkan Data PKP" value="{{ $edit['pkp'] }}">
        </div>
        <div class="form-group">
            <label for="alamat" class="form-label"> Alamat </label>
            <textarea name="alamat" class="form-control" id="alamat" rows="5" placeholder="Masukkan Alamat">{{ $edit['alamat'] }}</textarea>
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
