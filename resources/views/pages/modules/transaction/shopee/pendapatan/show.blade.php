@extends('pages.layouts.app')

@push('title_module', 'Detail Shopee Pendapatan')

@push('css_style')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        .mapping-list {
            max-height: 60vh;
            overflow-y: auto;
        }

        .draggable {
            cursor: grab;
            padding: 8px;
            margin-bottom: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #fff;
        }

        .draggable:hover {
            background: #f8f9fa;
        }

        .dropzone {
            border: 2px dashed #ccc;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .dropzone.active {
            border-color: #0d6efd;
            background: #f0f7ff;
        }

        .draggable.used {
            background-color: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .draggable.used::after {
            content: "✓ mapped";
            float: right;
            font-size: 11px;
            color: #198754;
        }

        .badge-excel {
            background: #e3f2fd;
            color: #0d47a1;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
        }
    </style>
@endpush

@push('content_app')

    <h1 class="h3 mb-4 text-gray-800">Detail Shopee Pendapatan</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        {{-- INFO FILE --}}
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi File</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>Seller</th>
                            <td>{{ $file->seller->nama }}</td>
                        </tr>
                        <tr>
                            <th>Periode</th>
                            <td>{{ $file->from_date }} / {{ $file->to_date }}</td>
                        </tr>
                        <tr>
                            <th>Total Data</th>
                            <td>{{ number_format($file->total_rows) }} baris</td>
                        </tr>
                    </table>

                    @if ($needMapping)
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i> Struktur file baru. Anda perlu melakukan mapping
                            kolom terlebih dahulu.
                        </div>
                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal"
                            data-target="#mappingModal">
                            <i class="fa fa-object-group"></i> Mulai Mapping Kolom
                        </button>
                    @else
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle"></i> Struktur file dikenali. Tidak perlu mapping ulang.
                        </div>
                        <form action="{{ url('/admin-panel/shopee/pendapatan/' . $file->id . '/process-database') }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="nama_seller" value="{{ $file->seller->nama }}">
                            <button type="submit" class="btn btn-success btn-block btn-lg">
                                <i class="fa fa-upload"></i> Konfirmasi Import
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Preview 20 Baris Pertama</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm" id="dataTable" width="100%" style="font-size: 10px;">
                    <thead>
                        <tr>
                            @foreach (array_keys($rows->first() ?? []) as $h)
                                <th class="bg-light">{{ $h }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $row)
                            <tr>
                                @foreach ($row as $v)
                                    <td>{{ Str::limit($v, 20) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mappingModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form method="POST"
                    action="{{ url('/admin-panel/shopee/pendapatan/' . $file->id . '/process-database') }}">
                    @csrf
                    <input type="hidden" name="nama_seller" value="{{ $file->seller->nama }}">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Mapping Kolom Excel ke Database</h5>
                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-5">
                                <h6 class="font-weight-bold"><i class="fa fa-file-excel"></i> Kolom dari Excel</h6>
                                <input type="text" class="form-control form-control-sm mb-2"
                                    placeholder="Cari kolom Excel..." id="searchExcel">
                                <div class="mapping-list" id="excelList">
                                    @foreach (array_keys($rows->first() ?? []) as $key)
                                        <div class="draggable" draggable="true" data-excel="{{ $key }}">
                                            {{ $key }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-md-2 text-center d-flex align-items-center justify-content-center">
                                <i class="fa fa-arrow-right fa-2x text-muted d-none d-md-block"></i>
                            </div>

                            <div class="col-md-5">
                                <h6 class="font-weight-bold"><i class="fa fa-database"></i> Kolom di Database</h6>
                                <input type="text" class="form-control form-control-sm mb-2"
                                    placeholder="Cari kolom DB..." id="searchDb">
                                <div class="mapping-list" id="dbList">
                                    @foreach ($dbColumns as $col)
                                        <div class="dropzone" data-db="{{ $col }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="font-weight-bold">{{ $col }}</span>
                                                <span class="mapped-text text-muted small">Belum di-map</span>
                                            </div>
                                            <input type="hidden" name="mapping[{{ $col }}]" value="">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan & Proses Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endpush

@push('js_style')
    <script src="{{ asset('templating/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('templating/js/demo/datatables-demo.js') }}"></script>
    <script>
        let draggedElement = null;

        document.querySelectorAll('.draggable').forEach(item => {
            item.addEventListener('dragstart', function(e) {
                draggedElement = this;
                e.dataTransfer.setData('text/plain', this.dataset.excel);
                this.style.opacity = '0.4';
            });

            item.addEventListener('dragend', function() {
                this.style.opacity = '1';
            });
        });

        document.querySelectorAll('.dropzone').forEach(zone => {
            zone.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('active');
            });

            zone.addEventListener('dragleave', function() {
                this.classList.remove('active');
            });

            zone.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('active');

                const excelHeader = e.dataTransfer.getData('text/plain');
                const dbColumn = this.dataset.db;

                this.querySelector('.mapped-text').innerHTML =
                    `<span class="badge-excel">← ${excelHeader}</span>`;
                this.querySelector('input').value = excelHeader;
                this.style.borderColor = '#198754';
                this.style.background = '#f1fcf6';

                if (draggedElement) {
                    draggedElement.classList.add('used');
                }
            });
        });

        document.getElementById('searchExcel').addEventListener('input', function() {
            let val = this.value.toLowerCase();
            document.querySelectorAll('#excelList .draggable').forEach(el => {
                el.style.display = el.innerText.toLowerCase().includes(val) ? 'block' : 'none';
            });
        });

        document.getElementById('searchDb').addEventListener('input', function() {
            let val = this.value.toLowerCase();
            document.querySelectorAll('#dbList .dropzone').forEach(el => {
                el.style.display = el.innerText.toLowerCase().includes(val) ? 'block' : 'none';
            });
        });
    </script>
@endpush
