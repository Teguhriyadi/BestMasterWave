@extends('pages.layouts.app')

@push('title_module', 'Tiktok Harga Modal')

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Ubah Harga Modal
    </h1>

    @if (session('success'))
        <div class="alert alert-success">
            <strong>Berhasil</strong>, {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            <strong>Gagal</strong>, {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fa fa-edit"></i> Ubah Harga Modal
                    </h6>
                </div>
                <form action="{{ url('/admin-panel/tiktok-harga-modal') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <strong>Catatan:</strong> Filter Ke Kolom :
                            <strong>
                                Waktu Pesanan Dibuat
                            </strong>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dari" class="form-label font-weight-bold">Dari Tanggal</label>
                                    <input type="date" class="form-control" name="dari" id="dari">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sampai" class="form-label font-weight-bold">Sampai Tanggal</label>
                                    <input type="date" class="form-control" name="sampai" id="sampai">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="harga_modal" class="form-label font-weight-bold">Harga Modal</label>
                            <input type="text" class="form-control" name="harga_modal" id="harga_modal" placeholder="0"
                                min="1">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="reset" class="btn btn-danger btn-sm">
                            <i class="fa fa-times"></i> RESET
                        </button>
                        <button onclick="return confirm('Apakah Anda Yakin Ingin Mengubah Harga Modal?')" type="submit"
                            class="btn btn-success btn-sm">
                            <i class="fa fa-save"></i> SIMPAN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endpush

@push('js_style')
    <script type="text/javascript">
        const hargaInput = document.getElementById('harga_modal');

        function formatRupiah(value) {
            value = value.replace(/\D/g, '');
            return value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        hargaInput.addEventListener('input', function(e) {
            const cursorPos = this.selectionStart;
            const oldLength = this.value.length;

            this.value = formatRupiah(this.value);

            const newLength = this.value.length;
            const diff = newLength - oldLength;
            this.selectionStart = this.selectionEnd = cursorPos + diff;
        });

        const form = hargaInput.closest('form');
        if (form) {
            form.addEventListener('submit', function() {
                hargaInput.value = hargaInput.value.replace(/\./g, '');
            });
        }
    </script>
@endpush
