@extends('pages.layouts.app')

@push('title_module', 'Denda')

@push('css_style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet">
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Denda
    </h1>

    @if (session('success'))
        <div class="alert alert-success">
            <strong>Berhasil,</strong> {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            <strong>Gagal,</strong> {{ session('error') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{ url('/admin-panel/denda') }}" class="btn btn-danger btn-sm">
                <i class="fa fa-sign-out-alt"></i> Kembali
            </a>
        </div>
        <form action="{{ url('/admin-panel/denda') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="mb-3 row">
                    <label for="karyawan_id" class="col-sm-3 col-form-label">
                        Nama Karyawan
                        <small class="text-danger">*</small>
                    </label>
                    <div class="col-sm-6">
                        <select name="karyawan_id" class="form-control @error('karyawan_id') is-invalid @enderror"
                            id="karyawan_id">
                            <option value="">- Pilih -</option>
                            @foreach ($karyawan as $item)
                                <option value="{{ $item['id'] }}"
                                    {{ old('karyawan_id') == $item['id'] ? 'selected' : '' }}>
                                    {{ $item['nama'] }} - {{ $item['jabatan'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('karyawan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="supplier_id" class="col-sm-3 col-form-label">
                        Tanggal Denda
                        <small class="text-danger">*</small>
                    </label>
                    <div class="col-sm-6">
                        <input type="date" class="form-control @error('tanggal_denda') is-invalid @enderror"
                            name="tanggal_denda" id="tanggal_denda" value="{{ old('tanggal_denda') }}">
                        @error('tanggal_denda')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="periode_gaji" class="col-sm-3 col-form-label">
                        Periode Gaji
                        <small class="text-danger">*</small>
                    </label>
                    <div class="col-sm-6">
                        <input type="date" class="form-control @error('periode_gaji') is-invalid @enderror"
                            name="periode_gaji" id="periode_gaji" value="{{ old('periode_gaji') }}">
                        @error('periode_gaji')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm" id="btnAddItem">
                    <i class="fa fa-plus"></i> Tambah Data Pelanggaran
                </button>

                <div class="table-responsive mt-3 table-scroll-x">
                    <table class="table table-bordered" id="tableItem" style="display:none;">
                        <thead class="thead-light">
                            <tr>
                                <th>
                                    Jenis Denda
                                    <small class="text-danger">*</small>
                                </th>
                                <th>
                                    Kode Denda
                                    <small class="text-danger">*</small>
                                </th>
                                <th>
                                    Nominal Potongan
                                    <small class="text-danger">*</small>
                                </th>
                                <th>
                                    Keterangan
                                    <small class="text-danger">*</small>
                                </th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody id="itemBody">

                        </tbody>

                    </table>
                </div>
            </div>
            <div class="card-footer">
                <button type="reset" class="btn btn-secondary btn-sm">
                    <i class="fa fa-times"></i> Batalkan
                </button>
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
@endpush

@push('js_style')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script type="text/javascript">
        const dendaaList = @json($denda);
        let itemIndex = 0;

        $(document).ready(function() {
            $("#karyawan_id").select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: '- Pilih -',
                allowClear: true
            })

            $('#dataTable').DataTable({
                scrollX: true,
                autoWidth: false,
                responsive: false
            });

            initJenisDendaSelect();
        });

        function removeRow(id) {
            $("#row-" + id).remove();
            if ($("#itemBody tr").length === 0) {
                $("#tableItem").hide();
            }
        }

        function initJenisDendaSelect(context = document) {
            $(context).find('.jenis-denda-select').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: '- Pilih Jenis Denda -',
                allowClear: true
            });
        }

        $("#btnAddItem").on("click", function(e) {
            e.preventDefault();
            $("#tableItem").show();
            itemIndex++;

            let options = `<option value="">- Pilih Jenis Denda -</option>`;
            dendaaList.forEach(item => {
                options += `
                    <option value="${item.id}">
                        ${item.nama_jenis}
                    </option>
                `;
            });

            let row = `
                <tr id="row-${itemIndex}">
                    <td>
                        <select name="items[${itemIndex}][jenis_denda]"
                                class="form-control jenis-denda-select" data-index="${itemIndex}" required>
                            ${options}
                        </select>
                    </td>
                    <td>
                        <input type="text" name="items[${itemIndex}][kode]"
                            class="form-control" id="kode_${itemIndex}" placeholder="Masukkan Kode Denda" readonly>
                    </td>
                    <td>
                        <input type="number" name="items[${itemIndex}][nominal]"
                            class="form-control" id="nominal_${itemIndex}" placeholder="Masukkan Nominal Potongan" min="1" readonly>
                    </td>
                    <td>
                        <input type="text" name="items[${itemIndex}][keterangan]"
                            class="form-control" placeholder="Masukkan Keterangan" required>
                    </td>
                    <td class="text-center">
                        <button type="button"
                                class="btn btn-danger btn-sm"
                                onclick="removeRow(${itemIndex})">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
            $("#itemBody").append(row);
            initJenisDendaSelect(`#row-${itemIndex}`);
        });

        $(document).on('change', '.jenis-denda-select', function() {
            const dendaId = $(this).val();
            const index = $(this).data('index');

            if (!dendaId) return;

            const denda = dendaaList.find(item => item.id == dendaId);

            if (denda) {
                $('#kode_' + index).val(denda.kode ?? '');
                $('#nominal_' + index).val(denda.nominal ?? 0);
            }
        });
    </script>
@endpush
