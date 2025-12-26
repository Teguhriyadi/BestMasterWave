@extends('pages.layouts.app')

@push('title_module', 'Detail Shopee Pendapatan')

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

    @php
        // safety guard
        $newRowsCount = $newRowsCount ?? 0;
    @endphp

    <h1 class="h3 mb-4 text-gray-800">Detail Shopee Pendapatan</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- =========================
INFO FILE
========================= --}}
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

            {{-- =========================
        ACTION
        ========================= --}}
            @if ($needMapping)
                {{-- BENAR-BENAR PERTAMA KALI --}}
                <button class="btn btn-primary" data-toggle="modal" data-target="#mappingModal">
                    🔀 Process & Mapping
                </button>
            @elseif ($newRowsCount === 0)
                {{-- TIDAK ADA DATA BARU --}}
                <div class="alert alert-info mt-3">
                    ✔ Semua data pada file ini sudah pernah diproses.
                </div>
                <button class="btn btn-secondary" disabled>
                    ✔ Tidak Ada Data Baru
                </button>
            @else
                {{-- ADA DATA BARU --}}
                <form method="POST" action="{{ url('/admin-panel/shopee/pendapatan/' . $file->id . '/process-database') }}"
                    class="d-inline">
                    @csrf
                    <button class="btn btn-success">
                        ⚡ Proses {{ $newRowsCount }} Data Baru
                    </button>
                </form>

                {{-- optional: user mau cek mapping --}}
                <button class="btn btn-outline-secondary ms-2" data-toggle="modal" data-target="#mappingModal">
                    👁 Lihat Mapping
                </button>
            @endif
        </div>
    </div>

    {{-- =========================
MODAL MAPPING (AUTO PREFILL)
========================= --}}
    <div class="modal fade" id="mappingModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <form method="POST"
                    action="{{ url('/admin-panel/shopee/pendapatan/' . $file->id . '/process-database') }}">
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
                                        @php
                                            $isUsed = in_array($key, array_values($prefillMapping ?? []));
                                        @endphp
                                        <li class="list-group-item draggable {{ $isUsed ? 'used' : '' }}"
                                            draggable="{{ $isUsed ? 'false' : 'true' }}" data-excel="{{ $key }}">
                                            {{ $key }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            {{-- DATABASE --}}
                            <div class="col-md-6">
                                <h6>Kolom Database</h6>
                                <input type="text" class="form-control form-control-sm mb-2"
                                    placeholder="🔍 Cari kolom Database..." id="searchDb">

                                <ul class="list-group mapping-list" id="dbList">
                                    @foreach ($dbColumns as $col)
                                        @php
                                            $mappedExcel = $prefillMapping[$col] ?? null;
                                        @endphp

                                        <li class="list-group-item dropzone {{ $mappedExcel ? 'bg-light' : '' }}"
                                            data-db="{{ $col }}"
                                            @if ($mappedExcel) data-excel="{{ $mappedExcel }}" @endif>

                                            <strong>{{ $col }}</strong>

                                            @if ($mappedExcel)
                                                <div class="text-muted small">← {{ $mappedExcel }}</div>
                                            @endif

                                            <input type="hidden" name="mapping[{{ $col }}]"
                                                value="{{ $mappedExcel }}">
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                            <i class="fa fa-times"></i> Tutup
                        </button>

                        {{-- submit manual hanya kalau mau override --}}
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
        document.getElementById('searchExcel')?.addEventListener('input', function() {
            const k = this.value.toLowerCase();
            document.querySelectorAll('#excelList .draggable').forEach(i => {
                i.style.display = i.textContent.toLowerCase().includes(k) ? '' : 'none';
            });
        });

        document.getElementById('searchDb')?.addEventListener('input', function() {
            const k = this.value.toLowerCase();
            document.querySelectorAll('#dbList .dropzone').forEach(i => {
                i.style.display = i.textContent.toLowerCase().includes(k) ? '' : 'none';
            });
        });

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
