<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Import Income Excel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Overlay */
        #process-loading {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.85);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Box */
        .process-box {
            text-align: center;
            padding: 24px 32px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        /* Dot animation */
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

                <h4 class="mb-4">Upload Excel</h4>

                <input type="file" id="file" class="form-control mb-3">

                <div class="form-group d-none" id="seller-wrapper">
                    <label for="seller_id" class="form-label">Nama Seller</label>
                    <select name="seller_id" id="seller_id" class="form-control">
                        <option value="">- Pilih Seller -</option>
                        @foreach ($seller as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->nama }} - {{ $item->platform->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="date-filter" class="mt-4 d-none"></div>

                <div id="headers"></div>

                <button id="process" class="btn btn-success mt-4 d-none">
                    Proses Data
                </button>

                <div id="loading" class="d-none text-center my-3">
                    <div class="spinner-border text-primary mb-2" role="status"></div>
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
            <div class="mt-2 fw-semibold">Memproses data, mohon tunggu</div>
        </div>
    </div>

    <script>
        let uploadedFile = null;
        let selectedColumns = {};
        let headerHash = null;
        let schemaId = null;

        let fromDate = null;
        let toDate   = null;

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

            fetch('/admin-panel/pendapatan', {
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

                    schemaId   = res.schema_id;
                    headerHash = res.header_hash;

                    let html = '<h5 class="mt-3">Pilih Kolom</h5>';

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
                                    <label class="form-check-label">
                                        Convert ke Angka
                                    </label>
                                </div>
                            </div>
                        </div>`;
                    }

                    headersDiv.innerHTML = html;
                    document.getElementById('process').classList.remove('d-none');
                    document.getElementById('seller-wrapper').classList.remove('d-none');

                    const dateWrapper = document.getElementById('date-filter');
                    dateWrapper.innerHTML = '';

                    fromDate = res.from_date;
                    toDate   = res.to_date;

                    if (res.date_columns && Object.keys(res.date_columns).length > 0) {

                        let dateHtml = `
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h6 class="text-primary mb-2">Filter Periode</h6>
                                    <div class="text-muted mb-2">
                                        Periode: <strong>${res.from_date}</strong> s/d <strong>${res.to_date}</strong>
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
                overlay.classList.add('d-none');
                btn.disabled = false;
                btn.innerText = 'Proses Data';
                return;
            }

            btn.disabled = true;
            btn.innerText = 'Processing...';
            overlay.classList.remove('d-none');

            let fd = new FormData();
            fd.append('file', uploadedFile);

            document.querySelectorAll('.column:checked').forEach(el => {
                fd.append(`columns[${el.dataset.col}]`, el.value);
            });

            document.querySelectorAll('.convert-number:checked').forEach(el => {
                fd.append(`convert[${el.dataset.col}]`, 1);
            });

            fd.append('schema_id', schemaId);
            fd.append('seller_id', sellerId);
            fd.append('header_hash', headerHash);

            const dateColumn = document.querySelector('input[name="date_column"]:checked');

            if (!dateColumn) {
                alert('Silakan pilih kolom tanggal untuk filter');
                overlay.classList.add('d-none');
                btn.disabled = false;
                btn.innerText = 'Proses Data';
                return;
            }

            fd.append('from_date', fromDate);
            fd.append('to_date', toDate);
            fd.append('date_column', dateColumn.value);

            fetch('/admin-panel/pendapatan/process', {
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

</body>

</html>
