<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Preview Import Income</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .modal-body {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }

        .mapping-list {
            max-height: 60vh;
            overflow-y: auto;
        }

        .draggable {
            cursor: grab;
        }

        .dropzone.bg-light {
            border: 2px dashed #0d6efd;
        }

        .draggable.used {
            background-color: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
        }

        .draggable.used::after {
            content: "✓ mapped";
            float: right;
            font-size: 11px;
            color: #198754;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container py-5">

        {{-- INFO FILE --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5>Informasi File</h5>
                <table class="table table-sm">
                    <tr>
                        <th>Seller</th>
                        <td>{{ $file->seller->nama }}</td>
                    </tr>
                    <tr>
                        <th>Platform</th>
                        <td>{{ $file->seller->platform->nama }}</td>
                    </tr>
                    <tr>
                        <th>Periode</th>
                        <td>{{ $file->from_date }} s/d {{ $file->to_date }}</td>
                    </tr>
                    <tr>
                        <th>Total Baris</th>
                        <td>{{ number_format($file->total_rows) }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Chunk</th>
                        <td>{{ $chunkCount }}</td>
                    </tr>
                </table>

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mappingModal">
                    🔀 Process & Mapping
                </button>
            </div>
        </div>

        {{-- PREVIEW --}}
        <h5>Preview (20 baris pertama)</h5>
        <pre class="bg-white p-3 small">{{ json_encode($rows->first(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>

    </div>

    {{-- MODAL MAPPING --}}
    <div class="modal fade" id="mappingModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <form method="POST"
                    action="{{ url('/admin-panel/shopee/pesanan/' . $file->id . '/process-database') }}">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Mapping Kolom Excel → Database</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">

                            {{-- EXCEL --}}
                            <div class="col-md-6">
                                <h6>Kolom Excel</h6>
                                <input type="text" class="form-control form-control-sm mb-2"
                                    placeholder="🔍 Cari kolom Excel..." id="searchExcel">
                                <ul class="list-group mapping-list" id="excelList">
                                    @foreach (array_keys($rows->first() ?? []) as $key)
                                        <li class="list-group-item draggable" draggable="true"
                                            data-excel="{{ $key }}">
                                            {{ $key }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            {{-- DB --}}
                            <div class="col-md-6">
                                <h6>Kolom Database</h6>
                                <input type="text" class="form-control form-control-sm mb-2"
                                    placeholder="🔍 Cari kolom Database..." id="searchDb">

                                <ul class="list-group mapping-list" id="dbList">
                                    @foreach ($dbColumns as $col)
                                        <li class="list-group-item dropzone" data-db="{{ $col }}">
                                            {{ $col }}
                                            <input type="hidden" name="mapping[{{ $col }}]" value="">
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                        </div>
                    </div>


                    <div class="modal-footer">
                        <button class="btn btn-success">✔ Proses Data</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('searchExcel').addEventListener('input', function() {
            const keyword = this.value.toLowerCase();

            document.querySelectorAll('#excelList .draggable').forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(keyword) ? '' : 'none';
            });
        });

        document.getElementById('searchDb').addEventListener('input', function() {
            const keyword = this.value.toLowerCase();

            document.querySelectorAll('#dbList .dropzone').forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(keyword) ? '' : 'none';
            });
        });
    </script>

    <script>
        let dragged = null;

        document.querySelectorAll('.draggable').forEach(el => {
            el.addEventListener('dragstart', e => {
                if (el.classList.contains('used')) {
                    e.preventDefault();
                    return;
                }
                dragged = el;
            });
        });


        document.querySelectorAll('.dropzone').forEach(zone => {

            zone.addEventListener('dragover', e => {
                e.preventDefault();
                zone.classList.add('bg-light');
            });

            zone.addEventListener('dragleave', () => {
                zone.classList.remove('bg-light');
            });

            zone.addEventListener('drop', e => {
                e.preventDefault();
                zone.classList.remove('bg-light');

                if (!dragged) return;

                const excel = dragged.dataset.excel;
                const db = zone.dataset.db;

                /**
                 * Jika zone sudah punya mapping sebelumnya,
                 * kembalikan excel lama ke state aktif
                 */
                const oldExcel = zone.dataset.excel;
                if (oldExcel) {
                    const oldEl = document.querySelector(`.draggable[data-excel="${oldExcel}"]`);
                    if (oldEl) oldEl.classList.remove('used');
                }

                // set mapping baru
                zone.dataset.excel = excel;

                zone.innerHTML = `
            <strong>${db}</strong>
            <div class="text-muted small">← ${excel}</div>
            <input type="hidden" name="mapping[${db}]" value="${excel}">
        `;

                // tandai excel sudah dipakai
                dragged.classList.add('used');
                dragged = null;
            });
        });
    </script>

</body>

</html>
