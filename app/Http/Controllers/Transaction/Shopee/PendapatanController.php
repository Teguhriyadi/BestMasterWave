<?php

namespace App\Http\Controllers\Transaction\Shopee;

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

            $platform = Platform::where('slug', 'shopee')->first();
            $data['seller'] = Seller::where('status', '1')
                ->where('platform_id', $platform->id)->get();

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
        $request->validate(['file' => 'required|mimes:xlsx,xls']);
        $path = $request->file('file')->getPathname();

        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);
        $reader->setReadFilter(new HeadersFilter);

        $spreadsheet = $reader->load($path);

        $headers = [];
        $sheetName = null;
        $headerRow = 6;
        $dataStart = 7;

        $allowedKeywords = ['income', 'penghasilan'];
        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            $title = $sheet->getTitle();

            $matched = false;
            foreach ($allowedKeywords as $keyword) {
                if (stripos($title, $keyword) !== false) {
                    $matched = true;
                    break;
                }
            }

            if (! $matched) {
                continue;
            }

            $sheetName = $sheet->getTitle();
            $highestColumn = $sheet->getHighestColumn();
            $maxCol = Coordinate::columnIndexFromString($highestColumn);

            for ($i = 1; $i <= $maxCol; $i++) {
                $col = Coordinate::stringFromColumnIndex($i);
                $val = trim((string) $sheet->getCell($col . $headerRow)->getValue());
                if ($val !== '') {
                    $headers[$col] = $val;
                }
            }
            break;
        }

        if (! $sheetName || empty($headers)) {
            return response()->json(['status' => false, 'message' => 'Format file tidak dikenali (Header baris 6 tidak ditemukan)'], 422);
        }

        $normalized = array_values($headers);
        $headerHash = hash('sha256', json_encode($normalized));

        $existingSchema = InvoiceSchemaPendapatan::where('header_hash', $headerHash)->first();

        $sheet = $spreadsheet->getSheetByName($sheetName);

        $dateColumns = $this->detectDateColumns($sheet, $headers, $dataStart);

        $fromRaw = $sheet->getCell('B2')->getValue();
        $toRaw = $sheet->getCell('C2')->getValue();

        $fromDate = $fromRaw ? (is_numeric($fromRaw) ? Date::excelToDateTimeObject($fromRaw)->format('Y-m-d') : date('Y-m-d', strtotime($fromRaw))) : date('Y-m-d');
        $toDate = $toRaw ? (is_numeric($toRaw) ? Date::excelToDateTimeObject($toRaw)->format('Y-m-d') : date('Y-m-d', strtotime($toRaw))) : date('Y-m-d');

        return response()->json([
            'status' => true,
            'headers' => $headers,
            'header_hash' => $headerHash,
            'schema_id' => $existingSchema ? $existingSchema->id : null,
            'date_columns' => $dateColumns,
            'from_date' => $fromDate,
            'to_date' => $toDate,
        ]);
    }

    public function process(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'columns' => 'required|array',
            'seller_id' => 'required',
            'date_column' => 'required|string',
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'header_hash' => 'required',
        ]);

        $reader = new Reader;

        try {
            $path = $request->file('file')->getPathname();
            $reader->open($path);

            $allExisting = [];
            ShopeePendapatan::select('no_pesanan')->chunk(5000, function ($rows) use (&$allExisting) {
                foreach ($rows as $row) {
                    $allExisting[strtoupper(trim((string) $row->no_pesanan))] = true;
                }
            });

            DB::reconnect();

            // --- TAMBAHAN: Cari schema lama berdasarkan hash ---
            $existingSchema = InvoiceSchemaPendapatan::where('header_hash', $request->header_hash)->first();

            DB::beginTransaction();

            $fileEntry = InvoiceFilePendapatan::create([
                'id' => (string) Str::uuid(),
                'seller_id' => $request->seller_id,
                'schema_id' => $existingSchema ? $existingSchema->id : null, // Hubungkan otomatis jika ada
                'header_hash' => $request->header_hash,
                'uploaded_at' => now(),
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'total_rows' => 0,
            ]);

            $buffer = [];
            $chunkIdx = 0;
            $totalNew = 0;
            $headerFoundAt = -1;

            foreach ($reader->getSheetIterator() as $sheet) {
                $sheetName = strtoupper($sheet->getName());
                if (strpos($sheetName, 'INCOME') === false && strpos($sheetName, 'PENGHASILAN') === false) continue;

                $currentRowNumber = 0;
                $currentSheetHeaderLine = -1;
                $noPesananColIndex = -1;
                $dateColIndex = -1;
                $colMap = [];

                foreach ($sheet->getRowIterator() as $row) {
                    $currentRowNumber++;
                    $cells = $row->toArray();

                    if ($currentSheetHeaderLine === -1) {
                        $rowString = strtoupper(json_encode($cells));
                        if (strpos($rowString, 'NO. PESANAN') !== false) {
                            $currentSheetHeaderLine = $currentRowNumber;
                            $headerFoundAt = $currentRowNumber;

                            foreach ($cells as $idx => $val) {
                                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($idx + 1);
                                if (isset($request->columns[$colLetter])) {
                                    $label = $request->columns[$colLetter];
                                    $colMap[$idx] = $label;
                                    if ($label === 'No. Pesanan') $noPesananColIndex = $idx;
                                }
                                if ($colLetter === $request->date_column) $dateColIndex = $idx;
                            }
                        }
                        continue;
                    }

                    if ($currentRowNumber > $currentSheetHeaderLine) {
                        if ($noPesananColIndex === -1 || $dateColIndex === -1) continue;

                        $valNoPesanan = $cells[$noPesananColIndex] ?? null;
                        $noPesanan = strtoupper(trim((string) $valNoPesanan));

                        if ($noPesanan === '' || isset($allExisting[$noPesanan])) continue;

                        $rawDate = $cells[$dateColIndex] ?? null;
                        if ($rawDate instanceof \DateTimeInterface) {
                            $rowDate = $rawDate->format('Y-m-d');
                        } else {
                            $timestamp = strtotime((string) $rawDate);
                            $rowDate = $timestamp ? date('Y-m-d', $timestamp) : null;
                        }

                        if (!$rowDate || $rowDate < $request->from_date || $rowDate > $request->to_date) continue;

                        $item = [];
                        foreach ($colMap as $idx => $label) {
                            $val = $cells[$idx] ?? '';
                            $item[$label] = ($val instanceof \DateTimeInterface) ? $val->format('Y-m-d H:i:s') : (string) $val;
                        }

                        $buffer[] = $item;
                        $totalNew++;
                        $allExisting[$noPesanan] = true;

                        if (count($buffer) >= 200) {
                            if (!DB::connection()->getPdo()) DB::reconnect();
                            InvoiceDataPendapatan::create([
                                'invoice_file_pendapatan_id' => $fileEntry->id,
                                'chunk_index' => $chunkIdx++,
                                'payload' => ['rows' => $buffer],
                            ]);
                            $buffer = [];
                            if ($chunkIdx % 10 === 0) gc_collect_cycles();
                        }
                    }
                }
            }

            $reader->close();

            if (!empty($buffer)) {
                InvoiceDataPendapatan::create([
                    'invoice_file_pendapatan_id' => $fileEntry->id,
                    'chunk_index' => $chunkIdx,
                    'payload' => ['rows' => $buffer],
                ]);
            }

            if ($totalNew === 0) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'Data sudah ada atau format salah.'], 422);
            }

            $fileEntry->update(['total_rows' => $totalNew]);
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => "Berhasil mengimpor $totalNew data baru.",
                'redirect' => url("/admin-panel/shopee/pendapatan/{$fileEntry->id}/show"),
            ]);
        } catch (\Throwable $e) {
            if (isset($reader)) $reader->close();
            if (DB::transactionLevel() > 0) DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        $file = InvoiceFilePendapatan::with(['seller.platform', 'schema', 'chunks'])->findOrFail($id);

        // --- TAMBAHAN: Re-check Schema jika schema_id masih null ---
        if (is_null($file->schema_id)) {
            $existingSchema = InvoiceSchemaPendapatan::where('header_hash', $file->header_hash)->first();
            if ($existingSchema) {
                $file->update(['schema_id' => $existingSchema->id]);
                $file->load('schema'); // Reload agar data schema muncul di view
            }
        }

        $firstChunk = $file->chunks->first();

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
            ]))->values();

        return view('pages.modules.transaction.shopee.pendapatan.show', [
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
        $file = InvoiceFilePendapatan::findOrFail($fileId);

        // Ambil mapping dari input (jika mapping baru) atau dari schema yang sudah terhubung
        $mapping = $request->input('mapping') ?? ($file->schema ? $file->schema->columns_mapping : null);

        if (!$mapping) {
            return back()->with('error', 'Mapping kolom tidak ditemukan. Silakan lakukan mapping terlebih dahulu.');
        }

        $nama_seller = $request->nama_seller;
        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        try {
            // 1. Simpan/Update Schema
            $schema = InvoiceSchemaPendapatan::updateOrCreate(
                ['header_hash' => $file->header_hash],
                ['columns_mapping' => $mapping]
            );

            $file->update(['schema_id' => $schema->id]);

            // 2. Proses per Chunk
            $chunkIds = InvoiceDataPendapatan::where('invoice_file_pendapatan_id', $fileId)
                ->orderBy('chunk_index', 'asc')
                ->pluck('id');

            foreach ($chunkIds as $id) {
                if (!DB::connection()->getPdo()) DB::reconnect();

                $chunk = InvoiceDataPendapatan::find($id);
                $rows = $chunk->payload['rows'] ?? [];
                $batchData = [];

                foreach ($rows as $row) {
                    $headerNoPesanan = $mapping['no_pesanan'] ?? 'No. Pesanan';
                    $noPesanan = strtoupper(trim((string) ($row[$headerNoPesanan] ?? '')));

                    if (empty($noPesanan)) continue;

                    $saveData = [
                        'uuid'            => (string) \Illuminate\Support\Str::uuid(),
                        'nama_seller'     => $nama_seller,
                        'no_pesanan'      => $noPesanan,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ];

                    foreach ($mapping as $dbColumn => $excelHeader) {
                        if (empty($excelHeader) || $dbColumn === 'no_pesanan') continue;

                        $value = $row[$excelHeader] ?? null;
                        if (in_array($dbColumn, ['total_penghasilan', 'biaya_admin', 'biaya_layanan', 'biaya_proses'])) {
                            $saveData[$dbColumn] = $this->parseNumber($value);
                        } else {
                            $saveData[$dbColumn] = $value;
                        }
                    }
                    $batchData[] = $saveData;
                }

                if (!empty($batchData)) {
                    DB::transaction(function () use ($batchData, $mapping) {
                        $updateColumns = array_merge(array_keys($mapping), ['updated_at', 'nama_seller']);
                        ShopeePendapatan::upsert($batchData, ['no_pesanan'], $updateColumns);
                    });
                }

                unset($batchData, $rows);
                gc_collect_cycles();
            }

            $file->update(['processed_at' => now()]);
            return redirect('/admin-panel/shopee/pendapatan')->with('success', 'Data berhasil diproses ke database.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
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
        $data['seller'] = $this->seller_service->list();

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
                    return '<a href="' . url('/admin-panel/shopee/pendapatan/data/' . $row->uuid . '/detail') . '"
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
                return redirect()->to('/admin-panel/shopee/pendapatan/data')->with('error', 'Data Tidak Ditemukan');
            }

            DB::commit();

            return view('pages.modules.transaction.shopee.pendapatan.detail', $data);
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->to('/admin-panel/shopee/pendapatan/data')->with('error', $e->getMessage());
        }
    }
}
