<?php

namespace App\Http\Controllers\Transaction\Shopee;

use App\Helpers\AuthDivisi;
use App\Http\Controllers\Controller;
use App\Http\Services\SellerService;
use App\Imports\ReadFilters\HeadersFilter;
use App\Models\InvoiceDataPendapatan;
use App\Models\InvoiceFilePendapatan;
use App\Models\InvoiceSchemaPendapatan;
use App\Models\Platform;
use App\Models\Seller;
use App\Models\ShopeePendapatan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use OpenSpout\Reader\XLSX\Reader;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class PendapatanController extends Controller
{
    public function __construct(
        protected SellerService $seller_service
    ) {}

    public function index()
    {
        try {

            DB::beginTransaction();

            if (empty(Auth::user()->one_divisi_roles)) {
                return redirect()->to("/admin-panel/shopee-pendapatan/data");
            }

            $platform = Platform::where('slug', 'shopee')->first();
            $data['seller'] = Seller::where('status', '1')
                // ->where('platform_id', $platform->id)
                ->get();

            DB::commit();

            return view('pages.modules.transaction.shopee.pendapatan.upload', $data);
        } catch (\Exception $e) {

            DB::rollBack();

            dd($e->getMessage());
        }
    }

    public function detectDateColumns($sheet, $headers, $dataStart)
    {
        $dateColumns = [];
        $keywords = ['waktu', 'tanggal', 'date', 'time', 'dibuat', 'dilepaskan', 'selesai'];

        foreach ($headers as $col => $label) {
            $lowerLabel = strtolower($label);

            $isDateLabel = false;
            foreach ($keywords as $kw) {
                if (str_contains($lowerLabel, $kw)) {
                    $isDateLabel = true;
                    break;
                }
            }

            if ($isDateLabel) {
                $sample = $sheet->getCell($col . $dataStart)->getValue();

                if ($sample) {
                    if (is_numeric($sample) && $sample > 40000) {
                        $dateColumns[$col] = $label;
                    } elseif (strtotime((string) $sample)) {
                        $dateColumns[$col] = $label;
                    }
                }
            }
        }

        return ! empty($dateColumns) ? $dateColumns : $headers;
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $path = $request->file('file')->getPathname();

        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);
        $reader->setReadFilter(new HeadersFilter);

        $spreadsheet = $reader->load($path);

        $headers    = [];
        $sheetName  = null;
        $headerRow  = 6;
        $dataStart  = 7;

        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            if (!preg_match('/income|penghasilan/i', $sheet->getTitle())) {
                continue;
            }

            $sheetName = $sheet->getTitle();
            $maxCol    = Coordinate::columnIndexFromString($sheet->getHighestColumn());

            for ($i = 1; $i <= $maxCol; $i++) {
                $col = Coordinate::stringFromColumnIndex($i);
                $val = trim((string) $sheet->getCell($col . $headerRow)->getValue());
                if ($val !== '') {
                    $headers[$col] = $val;
                }
            }
            break;
        }

        if (!$sheetName || empty($headers)) {
            return response()->json([
                'status'  => false,
                'message' => 'Header tidak ditemukan'
            ], 422);
        }

        $headerHash = hash('sha256', json_encode(array_values($headers)));
        $divisiId   = AuthDivisi::id();

        $existingSchema = InvoiceSchemaPendapatan::where([
            'header_hash' => $headerHash,
            'divisi_id'   => $divisiId,
        ])->first();

        $sheet = $spreadsheet->getSheetByName($sheetName);
        $dateColumns = $this->detectDateColumns($sheet, $headers, $dataStart);

        // === HANDLE FROM DATE ===
        $fromRaw = $sheet->getCell('B2')->getValue();
        if (is_numeric($fromRaw)) {
            $fromDate = Date::excelToDateTimeObject($fromRaw)->format('Y-m-d');
        } else {
            $ts = strtotime((string) $fromRaw);
            $fromDate = $ts ? date('Y-m-d', $ts) : null;
        }

        // === HANDLE TO DATE ===
        $toRaw = $sheet->getCell('C2')->getValue();
        if (is_numeric($toRaw)) {
            $toDate = Date::excelToDateTimeObject($toRaw)->format('Y-m-d');
        } else {
            $ts = strtotime((string) $toRaw);
            $toDate = $ts ? date('Y-m-d', $ts) : null;
        }

        if (!$fromDate || !$toDate) {
            return response()->json([
                'status'  => false,
                'message' => 'Periode tanggal tidak valid'
            ], 422);
        }

        return response()->json([
            'status'        => true,
            'headers'       => $headers,
            'header_hash'   => $headerHash,
            'schema_id'     => $existingSchema?->id,
            'date_columns'  => $dateColumns,
            'from_date'     => $fromDate,
            'to_date'       => $toDate,
        ]);
    }

    public function process(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $request->validate([
            'file'        => 'required|mimes:xlsx,xls',
            'columns'     => 'required|array',
            'seller_id'   => 'required',
            'date_column' => 'required|string',
            'from_date'   => 'required|date',
            'to_date'     => 'required|date',
            'header_hash' => 'required',
        ]);

        $divisiId = AuthDivisi::id();

        $reader = new Reader();
        $reader->open($request->file('file')->getPathname());

        $schema = InvoiceSchemaPendapatan::where([
            'header_hash' => $request->header_hash,
            'divisi_id'   => $divisiId,
        ])->first();

        DB::beginTransaction();

        try {

            $file = InvoiceFilePendapatan::create([
                'id'          => (string) Str::uuid(),
                'divisi_id'   => $divisiId,
                'seller_id'   => $request->seller_id,
                'schema_id'   => $schema?->id,
                'header_hash' => $request->header_hash,
                'from_date'   => $request->from_date,
                'to_date'     => $request->to_date,
                'uploaded_at' => now(),
                'total_rows'  => 0,
            ]);

            // ambil no_pesanan existing (PER DIVISI)
            $existing = ShopeePendapatan::where('divisi_id', $divisiId)
                ->pluck('no_pesanan')
                ->map(fn($v) => strtoupper(trim($v)))
                ->flip()
                ->toArray();

            $buffer   = [];
            $chunkIdx = 0;
            $totalNew = 0;

            foreach ($reader->getSheetIterator() as $sheet) {

                if (!preg_match('/income|penghasilan/i', $sheet->getName())) {
                    continue;
                }

                $headerRowIndex = -1;
                $noPesananIndex = -1;
                $dateIndex      = -1;
                $columnIndexes  = [];

                foreach ($sheet->getRowIterator() as $rowIndex => $row) {

                    $cells = $row->toArray();

                    // cari header
                    if ($headerRowIndex === -1) {
                        $joined = strtoupper(json_encode($cells));

                        if (str_contains($joined, 'NO. PESANAN')) {
                            $headerRowIndex = $rowIndex;

                            foreach ($cells as $idx => $label) {
                                $label = trim((string) $label);
                                if ($label === '') continue;

                                if ($label === 'No. Pesanan') {
                                    $noPesananIndex = $idx;
                                }

                                foreach ($request->columns as $colLetter => $expectedLabel) {
                                    if ($label === $expectedLabel) {
                                        $columnIndexes[$expectedLabel] = $idx;
                                    }
                                }
                            }

                            $dateIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(
                                $request->date_column
                            ) - 1;
                        }

                        continue;
                    }

                    if ($rowIndex <= $headerRowIndex) {
                        continue;
                    }

                    if ($noPesananIndex === -1 || $dateIndex === -1) {
                        continue;
                    }

                    $noPesanan = strtoupper(trim((string) ($cells[$noPesananIndex] ?? '')));
                    if ($noPesanan === '' || isset($existing[$noPesanan])) {
                        continue;
                    }

                    // === HANDLE TANGGAL (OPEN SPOUT STYLE) ===
                    $rawDate = $cells[$dateIndex] ?? null;

                    if ($rawDate instanceof \DateTimeInterface) {
                        $rowDate = $rawDate->format('Y-m-d');
                    } else {
                        $timestamp = strtotime((string) $rawDate);
                        $rowDate = $timestamp ? date('Y-m-d', $timestamp) : null;
                    }

                    if (
                        !$rowDate ||
                        $rowDate < $request->from_date ||
                        $rowDate > $request->to_date
                    ) {
                        continue;
                    }

                    $item = [];
                    foreach ($columnIndexes as $label => $idx) {
                        $val = $cells[$idx] ?? null;
                        $item[$label] = $val instanceof \DateTimeInterface
                            ? $val->format('Y-m-d H:i:s')
                            : (string) $val;
                    }

                    $buffer[] = $item;
                    $existing[$noPesanan] = true;
                    $totalNew++;

                    if (count($buffer) === 200) {
                        InvoiceDataPendapatan::create([
                            'invoice_file_pendapatan_id' => $file->id,
                            'divisi_id'   => $divisiId,
                            'chunk_index' => $chunkIdx++,
                            'payload'    => ['rows' => $buffer],
                        ]);
                        $buffer = [];
                    }
                }
            }

            if (!empty($buffer)) {
                InvoiceDataPendapatan::create([
                    'invoice_file_pendapatan_id' => $file->id,
                    'divisi_id'   => $divisiId,
                    'chunk_index' => $chunkIdx,
                    'payload'    => ['rows' => $buffer],
                ]);
            }

            if ($totalNew === 0) {
                DB::rollBack();
                return response()->json([
                    'status'  => false,
                    'message' => 'Data sudah ada atau tidak sesuai periode'
                ], 422);
            }

            $file->update(['total_rows' => $totalNew]);

            DB::commit();
            $reader->close();

            return response()->json([
                'status'  => true,
                'message' => "Berhasil import {$totalNew} data",
                'redirect' => url("/admin-panel/shopee-pendapatan/{$file->id}/show"),
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();
            $reader->close();

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id)
    {
        $file = InvoiceFilePendapatan::with(['seller.platform', 'schema', 'chunks'])->findOrFail($id);

        if (is_null($file->schema_id)) {
            $existingSchema = InvoiceSchemaPendapatan::where('header_hash', $file->header_hash)->first();
            if ($existingSchema) {
                $file->update(['schema_id' => $existingSchema->id]);
                $file->load('schema');
            }
        }

        $firstChunk = $file->chunks->first();

        $excludedColumns = collect([
            'no',
            'cek'
        ]);

        $rowsPayload = collect(data_get($firstChunk?->payload, 'rows', []));

        $excelHeaders = collect(array_keys($rowsPayload->first() ?? []))
            ->reject(function ($header) use ($excludedColumns) {
                $normalized = Str::of($header)
                    ->lower()
                    ->replaceMatches('/[^a-z0-9]/', '');

                return $excludedColumns->contains($normalized);
            })
            ->values();

        $needMapping = is_null($file->processed_at) && is_null($file->schema_id);

        $dbColumns = collect(DB::getSchemaBuilder()->getColumnListing('shopee_pendapatan'))
            ->reject(fn($c) => in_array($c, [
                'id',
                'uuid',
                'created_at',
                'updated_at',
                'nama_seller',
                'harga_modal',
                'seller_id',
                'invoice_file_id',
                'divisi_id'
            ]))->values();

        return view('pages.modules.transaction.shopee.pendapatan.show', [
            'file' => $file,
            'rows' => $excelHeaders,
            'chunkCount' => $file->chunks->count(),
            'dbColumns' => $dbColumns,
            'needMapping' => $needMapping,
            'prefillMapping' => $file->schema?->columns_mapping ?? [],
        ]);
    }

    public function processDatabase(Request $request, $fileId)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $file = InvoiceFilePendapatan::findOrFail($fileId);
        $divisiId = $file->divisi_id;

        $mapping = $request->mapping ?? $file->schema?->columns_mapping;

        if (!$mapping || !isset($mapping['no_pesanan'])) {
            return back()->with('error', 'Mapping tidak valid');
        }

        $schema = InvoiceSchemaPendapatan::updateOrCreate(
            [
                'header_hash' => $file->header_hash,
                'divisi_id'   => $divisiId
            ],
            [
                'columns_mapping' => $mapping
            ]
        );

        $file->update(['schema_id' => $schema->id]);

        $dbColumns = collect(DB::getSchemaBuilder()->getColumnListing('shopee_pendapatan'))
            ->reject(fn($c) => in_array($c, [
                'id',
                'uuid',
                'created_at',
                'updated_at'
            ]))
            ->values()
            ->toArray();

        InvoiceDataPendapatan::where([
            'invoice_file_pendapatan_id' => $fileId,
            'divisi_id' => AuthDivisi::id()
        ])
            ->select(['id', 'payload'])
            ->chunkById(10, function ($chunks) use (
                $mapping,
                $dbColumns,
                $request
            ) {

                foreach ($chunks as $chunk) {

                    $batch = [];

                    foreach ($chunk->payload['rows'] as $row) {

                        $noPesanan = strtoupper(trim(
                            (string) ($row[$mapping['no_pesanan']] ?? '')
                        ));

                        if ($noPesanan === '') {
                            continue;
                        }

                        $data = [
                            'uuid'        => (string) Str::uuid(),
                            'divisi_id'   => AuthDivisi::id(),
                            'no_pesanan'  => $noPesanan,
                            'nama_seller' => $request->nama_seller,
                            'created_at'  => now(),
                            'updated_at'  => now(),
                        ];

                        foreach ($mapping as $dbColumn => $excelHeader) {

                            if (
                                $dbColumn === 'no_pesanan' ||
                                empty($excelHeader) ||
                                !in_array($dbColumn, $dbColumns)
                            ) {
                                continue;
                            }

                            $data[$dbColumn] = $row[$excelHeader] ?? null;
                        }

                        $batch[] = $data;
                    }

                    if (!empty($batch)) {
                        ShopeePendapatan::upsert(
                            $batch,
                            ['divisi_id', 'no_pesanan'],
                            array_values(array_diff($dbColumns, ['no_pesanan']))
                        );
                    }

                    unset($batch);
                    gc_collect_cycles();
                }
            });

        $file->update(['processed_at' => now()]);

        return redirect()
            ->to("/admin-panel/shopee-pendapatan/")
            ->with('success', 'Data berhasil diproses ke database');
    }

    public function parseNumber($value)
    {
        if (is_numeric($value)) {
            return $value;
        }
        $clean = preg_replace('/[^-0-9,.]/', '', $value);

        return (float) str_replace(',', '.', $clean);
    }

    public function kelola(Request $request)
    {
        $data['seller'] = $this->seller_service->list_seller_all();

        if ($request->ajax()) {
            $query = ShopeePendapatan::query();

            if ($request->nama_seller) {
                $query->where('nama_seller', $request->nama_seller);
            }

            $filterBy = $request->filter_by;
            $dari = $request->dari;
            $sampai = $request->sampai;
            $allowedColumns = ['waktu_pesanan', 'tanggal_dana_dilepaskan'];

            if (in_array($filterBy, $allowedColumns) && $dari && $sampai) {
                $query->whereBetween($filterBy, [
                    $dari . ' 00:00:00',
                    $sampai . ' 23:59:59'
                ]);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('waktu_pesanan', function ($row) {
                    return $row->waktu_pesanan ? Carbon::parse($row->waktu_pesanan)->translatedFormat('d F Y') : '-';
                })
                ->editColumn('tanggal_dana_dilepaskan', function ($row) {
                    return $row->tanggal_dana_dilepaskan ? Carbon::parse($row->tanggal_dana_dilepaskan)->translatedFormat('d F Y') : '-';
                })
                ->editColumn('harga_asli', function ($row) {
                    return 'Rp ' . number_format($row->harga_asli, 0, ',', '.');
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . url('/admin-panel/shopee-pendapatan/data/' . $row->uuid . '/detail') . '"
                               class="btn btn-info btn-sm">
                               <i class="fa fa-search"></i> Detail
                            </a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.modules.transaction.shopee.pendapatan.kelola', $data);
    }

    public function detail($uuid)
    {
        try {

            DB::beginTransaction();

            $data['detail'] = ShopeePendapatan::where('uuid', $uuid)->first();

            if (empty($data['detail'])) {
                return redirect()->to('/admin-panel/shopee-pendapatan/data')->with('error', 'Data Tidak Ditemukan');
            }

            DB::commit();

            return view('pages.modules.transaction.shopee.pendapatan.detail', $data);
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->to('/admin-panel/shopee-pendapatan/data')->with('error', $e->getMessage());
        }
    }
}
