@extends('pages.layouts.app')

@push('title_module', 'Upload Excel Shopee Pendapatan')

@push('css_style')
    <style>
        #process-loading {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.85);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .process-box {
            text-align: center;
            padding: 24px 32px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .loader-dots span {
            display: inline-block;
            width: 10px;
            height: 10px;
            margin: 0 4px;
            background: #0d6efd;
            border-radius: 50%;
            animation: bounce 1.4s infinite ease-in-out both;
        }

        .loader-dots span:nth-child(1) { animation-delay: -0.32s; }
        .loader-dots span:nth-child(2) { animation-delay: -0.16s; }

        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0); opacity: .3; }
            40% { transform: scale(1); opacity: 1; }
        }
    </style>
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Shopee Pendapatan
    </h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session("error"))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fa fa-upload"></i> Upload Excel Shopee Pendapatan
            </h6>
        </div>
        <div class="card-body">

            <div class="alert alert-info">
                Pastikan file Excel memiliki header di <strong>Baris 6</strong> dan data dimulai dari <strong>Baris 7</strong>.
            </div>

            <input type="file" id="file" class="form-control mb-3" accept=".xlsx, .xls">

            <div class="form-group d-none" id="seller-wrapper">
                <label for="seller_id" class="form-label font-weight-bold">Nama Seller</label>
                <select name="seller_id" id="seller_id" class="form-control">
                    <option value="">- Pilih Seller -</option>
                    @foreach ($seller as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div id="date-filter" class="mt-4 d-none"></div>

            <div id="headers" class="mt-4"></div>

            <button id="process" class="btn btn-success mt-4 d-none">
                <i class="fa fa-cogs"></i> Proses Data
            </button>

            <div id="loading" class="d-none text-center my-3">
                <div class="spinner-border text-primary mb-2"></div>
                <div class="fw-semibold">Membaca file Excel...</div>
            </div>

        </div>
    </div>

    <div id="process-loading" class="d-none">
        <div class="process-box">
            <div class="loader-dots">
                <span></span><span></span><span></span>
            </div>
            <div class="mt-2 fw-semibold" id="process-text">Memproses data, mohon tunggu...</div>
        </div>
    </div>

@endpush

@push('js_style')
    <script>
        let uploadedFile = null;
        let schemaId = null;
        let headerHash = null;
        let allHeaders = {};
        let fromDate = null;
        let toDate = null;

        document.getElementById('file').addEventListener('change', function() {
            uploadedFile = this.files[0];
            if (!uploadedFile) return;

            // Reset state & UI
            const loading = document.getElementById('loading');
            const headersDiv = document.getElementById('headers');
            const dateWrapper = document.getElementById('date-filter');
            const processBtn = document.getElementById('process');
            const sellerWrapper = document.getElementById('seller-wrapper');

            loading.classList.remove('d-none');
            headersDiv.innerHTML = '';
            dateWrapper.innerHTML = '';
            dateWrapper.classList.add('d-none');
            processBtn.classList.add('d-none');
            sellerWrapper.classList.add('d-none');
            this.disabled = true;

            let fd = new FormData();
            fd.append('file', uploadedFile);

            fetch("{{ url('admin-panel/shopee/pendapatan') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: fd
            })
            .then(res => res.json())
            .then(res => {
                loading.classList.add('d-none');
                document.getElementById('file').disabled = false;

                if (!res.status) {
                    alert(res.message);
                    this.value = ''; // Reset input file
                    return;
                }

                // Simpan data ke variabel global
                schemaId = res.schema_id;
                headerHash = res.header_hash;
                allHeaders = res.headers;
                fromDate = res.from_date;
                toDate = res.to_date;

                // 1. Tampilkan Preview Header
                let html = `
                    <div class="card border-success">
                        <div class="card-header bg-success text-white py-2">
                            Kolom Excel Terdeteksi (${Object.keys(allHeaders).length})
                        </div>
                        <div class="card-body">
                            <div class="row">`;

                let i = 1;
                for (const col in allHeaders) {
                    html += `
                        <div class="col-md-6 mb-1">
                            <span class="badge badge-secondary mr-2">${i}</span>
                            <strong>${allHeaders[col]}</strong>
                            <small class="text-muted">(${col})</small>
                        </div>`;
                    i++;
                }
                html += `</div></div></div>`;
                headersDiv.innerHTML = html;

                // 2. Tampilkan Filter Tanggal
                if (res.date_columns && Object.keys(res.date_columns).length > 0) {
                    let dateHtml = `
                        <div class="card border-primary">
                            <div class="card-body">
                                <h6 class="text-primary font-weight-bold mb-2">Pilih Kolom Tanggal Acuan</h6>
                                <p class="small text-muted mb-3">Data akan difilter berdasarkan periode: <strong>${fromDate}</strong> s/d <strong>${toDate}</strong></p>`;

                    for (const col in res.date_columns) {
                        const isDefault = res.date_columns[col].toLowerCase().includes('tanggal dana dilepaskan');
                        dateHtml += `
                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="date_${col}" name="date_column" value="${col}" class="custom-control-input" ${isDefault ? 'checked' : ''}>
                                <label class="custom-control-label" for="date_${col}">${res.date_columns[col]} (${col})</label>
                            </div>`;
                    }
                    dateHtml += `</div></div>`;
                    dateWrapper.innerHTML = dateHtml;
                    dateWrapper.classList.remove('d-none');
                }

                // 3. Munculkan tombol proses & seller
                processBtn.classList.remove('d-none');
                sellerWrapper.classList.remove('d-none');
            })
            .catch(() => {
                loading.classList.add('d-none');
                document.getElementById('file').disabled = false;
                alert('Gagal membaca file Excel. Pastikan format benar.');
            });
        });

        document.getElementById('process').addEventListener('click', function() {
            const btn = this;
            const overlay = document.getElementById('process-loading');
            const sellerId = document.getElementById('seller_id').value;
            const dateColumn = document.querySelector('input[name="date_column"]:checked');

            if (!sellerId) {
                alert('Silakan pilih Nama Seller terlebih dahulu');
                return;
            }

            if (!dateColumn) {
                alert('Silakan pilih kolom tanggal sebagai acuan filter');
                return;
            }

            btn.disabled = true;
            overlay.classList.remove('d-none');

            let fd = new FormData();
            fd.append('file', uploadedFile);
            fd.append('seller_id', sellerId);
            fd.append('schema_id', schemaId || ''); // schemaId bisa null jika file baru
            fd.append('header_hash', headerHash);
            fd.append('from_date', fromDate);
            fd.append('to_date', toDate);
            fd.append('date_column', dateColumn.value);

            // Kirim semua mapping header mentah
            for (const col in allHeaders) {
                fd.append(`columns[${col}]`, allHeaders[col]);
            }

            fetch("{{ url('admin-panel/shopee/pendapatan/process') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: fd
            })
            .then(res => res.json())
            .then(res => {
                if (res.status && res.redirect) {
                    window.location.href = res.redirect;
                } else {
                    overlay.classList.add('d-none');
                    btn.disabled = false;
                    alert(res.message || 'Gagal memproses data');
                }
            })
            .catch(() => {
                overlay.classList.add('d-none');
                btn.disabled = false;
                alert('Terjadi kesalahan sistem saat memproses data.');
            });
        });
    </script>
@endpush
