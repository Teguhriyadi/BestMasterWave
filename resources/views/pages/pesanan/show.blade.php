@extends('pages.layouts.app')

@push('title_module', 'Detail Shopee Pesanan')

@push('css_style')
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
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">
        Detail Shopee Pesanan
    </h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

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

            <button class="btn btn-primary" data-toggle="modal" data-target="#mappingModal">
                🔀 Process & Mapping
            </button>
        </div>
    </div>

    <div class="modal fade" id="mappingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <form method="POST" action="{{ url('/admin-panel/shopee/pesanan/' . $file->id . '/process-database') }}">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Mapping Kolom Excel → Database</h5>
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
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times"></i> Batal
                        </button>
                        <button class="btn btn-success btn-sm">
                            <i class="fa fa-edit"></i> Proses Data
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

@endpush

@push('js_style')
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

                const oldExcel = zone.dataset.excel;
                if (oldExcel) {
                    const oldEl = document.querySelector(`.draggable[data-excel="${oldExcel}"]`);
                    if (oldEl) oldEl.classList.remove('used');
                }

                zone.dataset.excel = excel;

                zone.innerHTML = `
            <strong>${db}</strong>
            <div class="text-muted small">← ${excel}</div>
            <input type="hidden" name="mapping[${db}]" value="${excel}">
        `;

                dragged.classList.add('used');
                dragged = null;
            });
        });
    </script>
@endpush
