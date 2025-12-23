<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Import Pesanan Excel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="card shadow">
            <div class="card-body">

                <h4 class="mb-4">Upload Excel Pesanan</h4>

                <input type="file" id="file" class="form-control mb-3">

                <div class="form-group d-none mb-3" id="seller-wrapper">
                    <label class="form-label">Nama Seller</label>
                    <select id="seller_id" class="form-control">
                        <option value="">- Pilih Seller -</option>
                        @foreach ($seller as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->nama }} - {{ $item->platform->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

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
    </div>

    <div id="process-loading" class="d-none">
        <div class="process-box">
            <div class="loader-dots">
                <span></span><span></span><span></span>
            </div>
            <div class="mt-2 fw-semibold">Memproses data, mohon tunggu…</div>
        </div>
    </div>

    <script>
        let uploadedFile = null;
        let schemaId = null;
        let headerHash = null;
        let allHeaders = {};

        document.getElementById('file').addEventListener('change', function() {

            uploadedFile = this.files[0];
            if (!uploadedFile) return;

            const loading = document.getElementById('loading');
            const headersDiv = document.getElementById('headers');

            loading.classList.remove('d-none');
            headersDiv.innerHTML = '';
            this.disabled = true;

            const fd = new FormData();
            fd.append('file', uploadedFile);

            fetch('/admin-panel/shopee/pesanan', {
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
                                Semua kolom akan diproses otomatis.
                            </div>
                        </div>
                    </div>
                    `;
                    headersDiv.innerHTML = html;

                    document.getElementById('seller-wrapper').classList.remove('d-none');
                    document.getElementById('process').classList.remove('d-none');
                })
                .catch(() => {
                    loading.classList.add('d-none');
                    document.getElementById('file').disabled = false;
                    alert('Gagal membaca file');
                });
        });

        document.getElementById('process').addEventListener('click', function() {

            const sellerId = document.getElementById('seller_id').value;
            if (!sellerId) {
                alert('Silakan pilih Seller terlebih dahulu');
                return;
            }

            const overlay = document.getElementById('process-loading');
            overlay.classList.remove('d-none');
            this.disabled = true;
            this.innerText = 'Processing...';

            const fd = new FormData();
            fd.append('file', uploadedFile);
            fd.append('seller_id', sellerId);
            fd.append('schema_id', schemaId);
            fd.append('header_hash', headerHash);

            // kirim SEMUA kolom
            for (const col in allHeaders) {
                fd.append(`columns[${col}]`, allHeaders[col]);
            }

            fetch('/admin-panel/shopee/pesanan/process', {
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
                        alert(res.message || 'Gagal memproses data');
                    }
                })
                .catch(() => {
                    overlay.classList.add('d-none');
                    alert('Terjadi kesalahan');
                });
        });
    </script>

</body>

</html>
