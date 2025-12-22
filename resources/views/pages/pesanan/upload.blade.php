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

        .loader-dots span:nth-child(1) { animation-delay: -0.32s; }
        .loader-dots span:nth-child(2) { animation-delay: -0.16s; }

        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0); opacity: .3; }
            40% { transform: scale(1); opacity: 1; }
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

            <div id="headers"></div>

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

/* =============================
 * 1️⃣ UPLOAD FILE → AMBIL HEADER
 * ============================= */
document.getElementById('file').addEventListener('change', function () {

    uploadedFile = this.files[0];
    if (!uploadedFile) return;

    const loading = document.getElementById('loading');
    const headersDiv = document.getElementById('headers');

    loading.classList.remove('d-none');
    headersDiv.innerHTML = '';
    this.disabled = true;

    const fd = new FormData();
    fd.append('file', uploadedFile);

    fetch('/admin-panel/pesanan', {
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
            return;
        }

        schemaId   = res.schema_id;
        headerHash = res.header_hash;

        let html = `<h5 class="mt-3">Pilih Kolom</h5>`;

        for (const col in res.headers) {
            html += `
            <div class="row align-items-center mb-2">
                <div class="col-6">
                    <div class="form-check">
                        <input class="form-check-input column"
                               type="checkbox"
                               data-col="${col}"
                               value="${res.headers[col]}">
                        <label class="form-check-label">
                            ${res.headers[col]} (${col})
                        </label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-check text-secondary">
                        <input class="form-check-input convert-number"
                               type="checkbox"
                               data-col="${col}">
                        <label class="form-check-label">Convert ke Angka</label>
                    </div>
                </div>
            </div>`;
        }

        headersDiv.innerHTML = html;
        document.getElementById('process').classList.remove('d-none');
        document.getElementById('seller-wrapper').classList.remove('d-none');
    })
    .catch(() => {
        loading.classList.add('d-none');
        document.getElementById('file').disabled = false;
        alert('Gagal membaca file');
    });
});

/* =============================
 * 2️⃣ PROSES DATA
 * ============================= */
document.getElementById('process').addEventListener('click', function () {

    const sellerId = document.getElementById('seller_id').value;
    if (!sellerId) {
        alert('Silakan pilih Seller terlebih dahulu');
        return;
    }

    // 🔒 KOLOM WAJIB
    const required = ['No. Pesanan', 'Nomor Referensi SKU'];
    const selected = [];

    document.querySelectorAll('.column:checked').forEach(el => {
        selected.push(el.value);
    });

    for (const r of required) {
        if (!selected.includes(r)) {
            alert(`Kolom wajib dipilih: ${r}`);
            return;
        }
    }

    const overlay = document.getElementById('process-loading');
    overlay.classList.remove('d-none');
    this.disabled = true;

    const fd = new FormData();
    fd.append('file', uploadedFile);
    fd.append('schema_id', schemaId);
    fd.append('seller_id', sellerId);
    fd.append('header_hash', headerHash);

    document.querySelectorAll('.column:checked').forEach(el => {
        fd.append(`columns[${el.dataset.col}]`, el.value);
    });

    document.querySelectorAll('.convert-number:checked').forEach(el => {
        fd.append(`convert[${el.dataset.col}]`, 1);
    });

    fetch('/admin-panel/pesanan/process', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: fd
    })
    .then(res => res.json())
    .then(res => {
        if (res.status && res.redirect) {
            window.location.href = res.redirect;
        } else {
            alert('Gagal memproses data');
            overlay.classList.add('d-none');
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
