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

        .loader-dots span:nth-child(1) {
            animation-delay: -0.32s;
        }

        .loader-dots span:nth-child(2) {
            animation-delay: -0.16s;
        }

        @keyframes bounce {

            0%,
            80%,
            100% {
                transform: scale(0);
                opacity: .3;
            }

            40% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Data Shopee Pendapatan
    </h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session("error"))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fa fa-upload"></i> Upload Excel Shopee Pendapatan
            </h6>
        </div>
        <div class="card-body">

            <input type="file" id="file" class="form-control mb-3">

            <div class="form-group d-none" id="seller-wrapper">
                <label for="seller_id" class="form-label">Nama Seller</label>
                <select name="seller_id" id="seller_id" class="form-control">
                    <option value="">- Pilih Seller -</option>
                    @foreach ($seller as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="date-filter" class="mt-4 d-none"></div>

            <div id="headers" class="mt-4"></div>

            <button id="process" class="btn btn-success mt-4 d-none">
                Proses Data
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
            <div class="mt-2 fw-semibold">Memproses data, mohon tunggu</div>
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

            const loading = document.getElementById('loading');
            const headersDiv = document.getElementById('headers');

            loading.classList.remove('d-none');
            headersDiv.innerHTML = '';
            this.disabled = true;

            let fd = new FormData();
            fd.append('file', uploadedFile);

            fetch("{{ url('admin-panel/shopee/pendapatan') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: fd
                })
                .then(res => res.json())
                .then(res => {

                    loading.classList.add('d-none');
                    document.getElementById('file').disabled = false;

                    if (!res.status) {
                        alert(res.message);
                        return;
                    }

                    schemaId = res.schema_id;
                    headerHash = res.header_hash;
                    allHeaders = res.headers;
                    fromDate = res.from_date;
                    toDate = res.to_date;

                    /* =============================
                       TAMPILKAN SEMUA KOLOM (READ ONLY)
                       ============================= */
                    let html = `
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        Kolom Excel Terdeteksi (${Object.keys(allHeaders).length})
                    </div>
                    <div class="card-body">
                        <div class="row">
            `;

                    let i = 1;
                    for (const col in allHeaders) {
                        html += `
                    <div class="col-md-6 mb-1">
                        <span class="badge bg-secondary me-2">${i}</span>
                        <strong>${allHeaders[col]}</strong>
                        <span class="text-muted">(${col})</span>
                    </div>
                `;
                        i++;
                    }

                    html += `
                        </div>
                        <div class="alert alert-info mt-3 mb-0">
                            Semua kolom di atas akan diproses otomatis.
                        </div>
                    </div>
                </div>
            `;

                    headersDiv.innerHTML = html;

                    document.getElementById('process').classList.remove('d-none');
                    document.getElementById('seller-wrapper').classList.remove('d-none');

                    /* =============================
                       FILTER TANGGAL
                       ============================= */
                    const dateWrapper = document.getElementById('date-filter');
                    dateWrapper.innerHTML = '';

                    if (res.date_columns && Object.keys(res.date_columns).length > 0) {

                        let dateHtml = `
                    <div class="card border-primary mt-4">
                        <div class="card-body">
                            <h6 class="text-primary mb-2">Filter Periode</h6>
                            <div class="text-muted mb-2">
                                Periode: <strong>${fromDate}</strong> s/d <strong>${toDate}</strong>
                            </div>
                `;

                        for (const col in res.date_columns) {
                            dateHtml += `
                        <div class="form-check">
                            <input class="form-check-input date-column"
                                   type="radio"
                                   name="date_column"
                                   value="${col}">
                            <label class="form-check-label">
                                ${res.date_columns[col]} (${col})
                            </label>
                        </div>
                    `;
                        }

                        dateHtml += `
                        </div>
                    </div>
                `;

                        dateWrapper.innerHTML = dateHtml;
                        dateWrapper.classList.remove('d-none');
                    }
                })
                .catch(() => {
                    loading.classList.add('d-none');
                    document.getElementById('file').disabled = false;
                    alert('Gagal membaca file');
                });
        });

        document.getElementById('process').addEventListener('click', function() {

            const btn = this;
            const overlay = document.getElementById('process-loading');
            const sellerId = document.getElementById('seller_id').value;

            if (!sellerId) {
                alert('Silakan pilih Seller terlebih dahulu');
                return;
            }

            const dateColumn = document.querySelector('input[name="date_column"]:checked');
            if (!dateColumn) {
                alert('Silakan pilih kolom tanggal');
                return;
            }

            btn.disabled = true;
            btn.innerText = 'Processing...';
            overlay.classList.remove('d-none');

            let fd = new FormData();
            fd.append('file', uploadedFile);

            // kirim SEMUA kolom
            for (const col in allHeaders) {
                fd.append(`columns[${col}]`, allHeaders[col]);
            }

            fd.append('schema_id', schemaId);
            fd.append('seller_id', sellerId);
            fd.append('header_hash', headerHash);
            fd.append('from_date', fromDate);
            fd.append('to_date', toDate);
            fd.append('date_column', dateColumn.value);

            fetch("{{ url('admin-panel/shopee/pendapatan/process') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: fd
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status && res.redirect) {
                        window.location.href = res.redirect;
                    } else {
                        overlay.classList.add('d-none');
                        btn.disabled = false;
                        btn.innerText = 'Proses Data';
                        alert(res.message || 'Gagal memproses data');
                    }
                })
                .catch(() => {
                    overlay.classList.add('d-none');
                    btn.disabled = false;
                    btn.innerText = 'Proses Data';
                    alert('Gagal memproses data');
                });
        });
    </script>
@endpush
