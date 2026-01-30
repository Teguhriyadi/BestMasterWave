<?php

namespace App\Http\Controllers\Transaction\Tiktok;

use App\Helpers\AuthDivisi;
use App\Http\Controllers\Controller;
use App\Http\Mapper\TiktokMapper;
use App\Http\Services\SellerService;
use App\Models\InvoiceDataTiktokPendapatan;
use App\Models\InvoiceFileTiktokPendapatan;
use App\Models\InvoiceSchemaTiktokPendapatan;
use App\Models\Platform;
use App\Models\Seller;
use App\Models\TiktokPendapatan;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use OpenSpout\Reader\XLSX\Reader;
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
                return redirect()->to("/admin-panel/tiktok-pendapatan/data");
            }

            $platform = Platform::where('slug', 'tiktok')->first();
            $data['seller'] = Seller::where('status', '1')
                ->where('platform_id', $platform->id)->get();

            DB::commit();

            return view('pages.modules.transaction.tiktok.pendapatan.upload', $data);
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

        $targetSheet = 'Order details';
        $headers = [];

        try {
            $reader = ReaderEntityFactory::createXLSXReader();

            $reader->setShouldFormatDates(false);

            $reader->open($request->file('file')->getPathname());

            foreach ($reader->getSheetIterator() as $sheet) {

                if (strtolower($sheet->getName()) !== strtolower($targetSheet)) {
                    continue;
                }

                foreach ($sheet->getRowIterator() as $rowIndex => $row) {

                    if ($rowIndex !== 1) {
                        break;
                    }

                    foreach ($row->getCells() as $i => $cell) {
                        $value = trim((string) $cell->getValue());
                        if ($value !== '') {
                            $headers[$i] = $value;
                        }
                    }

                    break;
                }

                break;
            }

            $reader->close();

            if (empty($headers)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Header tidak ditemukan pada sheet Order details'
                ], 422);
            }

            return response()->json([
                'status'      => true,
                'headers'     => $headers,
                'header_hash' => md5(json_encode($headers))
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Server error',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function process(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $request->validate([
            'file'        => 'required|mimes:xlsx,xls',
            'seller_id'   => 'required',
            'header_hash' => 'required',
        ]);

        $divisiId = AuthDivisi::id();

        $schema = InvoiceSchemaTiktokPendapatan::where([
            'header_hash' => $request->header_hash,
            'divisi_id'   => $divisiId,
        ])->first();

        $reader = new Reader();
        $reader->open($request->file('file')->getPathname());

        try {
            $file = InvoiceFileTiktokPendapatan::create([
                'id'          => (string) Str::uuid(),
                'divisi_id'   => $divisiId,
                'seller_id'   => $request->seller_id,
                'schema_id'   => $schema?->id,
                'header_hash' => $request->header_hash,
                'uploaded_at' => now(),
                'total_rows'  => 0,
            ]);

            $columnMap     = [];
            $orderIdIndex  = null;
            $headerParsed  = false;

            $buffer   = [];
            $chunkIdx = 0;
            $totalNew = 0;

            foreach ($reader->getSheetIterator() as $sheet) {

                if (strtolower(trim($sheet->getName())) !== 'order details') {
                    continue;
                }

                foreach ($sheet->getRowIterator() as $rowIndex => $row) {

                    $cells = array_values($row->toArray());

                    /* ================= HEADER ================= */
                    if (!$headerParsed) {
                        foreach ($cells as $idx => $label) {
                            $label = trim((string) $label);
                            if ($label === '') continue;

                            // 🔥 NORMALISASI HEADER
                            $normalized = strtolower($label);
                            $normalized = preg_replace('/\s*\(.*?\)/', '', $normalized);
                            $normalized = str_replace(['/', ' '], '_', $normalized);

                            $columnMap[$normalized] = $idx;

                            if ($normalized === 'order_adjustment_id') {
                                $orderIdIndex = $idx;
                            }
                        }

                        if ($orderIdIndex === null) {
                            throw new \Exception('Kolom Order/adjustment ID tidak ditemukan');
                        }

                        $headerParsed = true;
                        continue;
                    }

                    /* ================= DATA ================= */
                    $orderId = $cells[$orderIdIndex] ?? null;
                    $orderId = strtoupper(trim((string) $orderId));

                    if ($orderId === '') {
                        continue;
                    }

                    // 🔥 CEK DUPLIKAT LANGSUNG KE DB (AMAN MEMORY)
                    $exists = TiktokPendapatan::where([
                        'divisi_id' => $divisiId,
                        'order_or_adjustment_id' => $orderId,
                    ])->exists();

                    if ($exists) {
                        continue;
                    }

                    $item = [];
                    foreach ($columnMap as $key => $idx) {
                        $item[$key] = $cells[$idx] ?? null;
                    }

                    $buffer[] = $item;
                    $totalNew++;

                    /* ================= FLUSH ================= */
                    if (count($buffer) === 200) {
                        DB::transaction(function () use ($buffer, $file, $divisiId, $chunkIdx) {
                            InvoiceDataTiktokPendapatan::create([
                                'invoice_file_tiktok_pendapatan_id' => $file->id,
                                'divisi_id'   => $divisiId,
                                'chunk_index' => $chunkIdx,
                                'payload'     => ['rows' => $buffer],
                            ]);
                        });

                        $buffer = [];
                        $chunkIdx++;
                    }
                }

                break;
            }

            if ($buffer) {
                InvoiceDataTiktokPendapatan::create([
                    'invoice_file_tiktok_pendapatan_id' => $file->id,
                    'divisi_id'   => $divisiId,
                    'chunk_index' => $chunkIdx,
                    'payload'     => ['rows' => $buffer],
                ]);
            }

            if ($totalNew === 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data baru yang diimport'
                ], 422);
            }

            $file->update(['total_rows' => $totalNew]);
            $reader->close();

            return response()->json([
                'status'   => true,
                'message'  => "Berhasil import {$totalNew} data",
                'redirect' => url("/admin-panel/tiktok-pendapatan/{$file->id}/show"),
            ]);
        } catch (\Throwable $e) {
            $reader->close();

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id)
    {
        $file = InvoiceFileTiktokPendapatan::with(['seller.platform', 'schema', 'chunks'])->findOrFail($id);

        if (is_null($file->schema_id)) {
            $existingSchema = InvoiceSchemaTiktokPendapatan::where('header_hash', $file->header_hash)->first();
            if ($existingSchema) {
                $file->update(['schema_id' => $existingSchema->id]);
                $file->load('schema');
            }
        }

        $firstChunk = $file->chunks->first();

        $needMapping = is_null($file->processed_at) && is_null($file->schema_id);

        $dbColumns = collect(DB::getSchemaBuilder()->getColumnListing('tiktok_pendapatan'))
            ->reject(fn($c) => in_array($c, [
                'id',
                'uuid',
                'created_at',
                'updated_at',
                'nama_seller',
                'harga_modal',
                'divisi_id',
                'created_by'
            ]))->values();

        return view('pages.modules.transaction.tiktok.pendapatan.show', [
            'file' => $file,
            'rows' => collect(data_get($firstChunk?->payload, 'rows', []))->take(20),
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

        $file = InvoiceFileTiktokPendapatan::with(['seller', 'schema'])
            ->findOrFail($fileId);

        $divisiId = $file->divisi_id;

        $mapping = $request->mapping ?? $file->schema?->columns_mapping;

        if (!$mapping || !isset($mapping['order_or_adjustment_id'])) {
            return back()->with('error', 'Mapping tidak valid');
        }

        $schema = InvoiceSchemaTiktokPendapatan::updateOrCreate(
            [
                'header_hash' => $file->header_hash,
                'divisi_id'   => $divisiId
            ],
            [
                'columns_mapping' => $mapping
            ]
        );

        $file->update(['schema_id' => $schema->id]);

        $dbColumns = collect(DB::getSchemaBuilder()->getColumnListing('tiktok_pendapatan'))
            ->reject(fn($c) => in_array($c, [
                'id',
                'uuid',
                'divisi_id',
                'created_by',
                'created_at',
                'updated_at'
            ]))
            ->values()
            ->toArray();

        $updateColumns = array_values(
            array_diff($dbColumns, ['order_or_adjustment_id'])
        );

        $now    = now();
        $userId = Auth::id();
        $seller = $request->nama_seller;

        $normalizeNumber = function ($value) {
            if ($value === null || $value === '') {
                return 0;
            }

            if (is_numeric($value)) {
                return (float) $value;
            }

            return (float) preg_replace('/[^0-9.-]/', '', $value);
        };

        InvoiceDataTiktokPendapatan::where([
            'invoice_file_tiktok_pendapatan_id' => $fileId,
            'divisi_id' => $divisiId
        ])->chunkById(300, function ($chunks) use (
            $mapping,
            $dbColumns,
            $updateColumns,
            $divisiId,
            $now,
            $userId,
            $seller,
            $normalizeNumber
        ) {

            foreach ($chunks as $chunk) {

                $batch = [];

                foreach ($chunk->payload['rows'] as $row) {

                    $noPesanan = strtoupper(trim(
                        (string) ($row[$mapping['order_or_adjustment_id']] ?? '')
                    ));

                    if ($noPesanan === '') {
                        continue;
                    }

                    $data = [
                        'uuid'        => (string) Str::uuid(),
                        'divisi_id'   => $divisiId,
                        'order_or_adjustment_id' => $noPesanan,
                        'nama_seller' => $seller,
                        'created_by'  => $userId,
                        'created_at'  => $now,
                        'updated_at'  => $now,
                    ];

                    foreach ($mapping as $dbColumn => $excelHeader) {

                        if (
                            $dbColumn === 'order_or_adjustment_id' ||
                            empty($excelHeader) ||
                            !isset($row[$excelHeader])
                        ) {
                            continue;
                        }

                        $value = $row[$excelHeader];

                        // angka
                        if (is_string($value) || is_numeric($value)) {
                            $value = $normalizeNumber($value);
                        }

                        // tanggal
                        if ($value instanceof \DateTimeInterface) {
                            $value = $value->format('Y-m-d H:i:s');
                        }

                        $data[$dbColumn] = $value;
                    }

                    $batch[] = $data;
                }

                if ($batch) {
                    TiktokPendapatan::upsert(
                        $batch,
                        ['divisi_id', 'order_or_adjustment_id'],
                        $updateColumns
                    );
                }

                unset($batch);
            }
        });

        $file->update(['processed_at' => now()]);

        return redirect()
            ->to("/admin-panel/tiktok-pendapatan")
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
            $query = TiktokPendapatan::query();

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
                    return '<a href="' . url('/admin-panel/tiktok-pendapatan/data/' . $row->uuid . '/detail') . '"
                               class="btn btn-info btn-sm">
                               <i class="fa fa-search"></i> Detail
                            </a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.modules.transaction.tiktok.pendapatan.kelola', $data);
    }

    public function detail($uuid)
    {
        try {

            DB::beginTransaction();

            $data['detail'] = TiktokPendapatan::where('uuid', $uuid)->first();

            if (empty($data['detail'])) {
                return redirect()->to('/admin-panel/tiktok-pendapatan/data')->with('error', 'Data Tidak Ditemukan');
            }

            $data["details"] = TiktokMapper::map($data["detail"]);

            DB::commit();

            return view('pages.modules.transaction.tiktok.pendapatan.detail', $data);
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->to('/admin-panel/shopee-pendapatan/data')->with('error', $e->getMessage());
        }
    }
}
