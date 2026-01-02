<?php

namespace App\Http\Controllers\Transaction\Shopee;

use App\Http\Controllers\Controller;
use App\Http\Services\SellerService;
use App\Models\InvoiceDataPendapatan;
use App\Models\InvoiceFilePendapatan;
use App\Models\InvoiceSchemaPendapatan;
use App\Models\Platform;
use App\Models\Seller;
use App\Models\ShopeePendapatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;

class PendapatanController extends Controller
{
    public function __construct(
        protected SellerService $seller_service
    ) {}

    public function index()
    {
        try {

            DB::beginTransaction();

            $platform = Platform::where("slug", "shopee")->first();
            $data["seller"] = Seller::where("status", "1")
                ->where("platform_id", $platform->id)->get();

            DB::commit();

            return view("pages.modules.transaction.shopee.pendapatan.upload", $data);
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
                    } elseif (strtotime((string)$sample)) {
                        $dateColumns[$col] = $label;
                    }
                }
            }
        }

        return !empty($dateColumns) ? $dateColumns : $headers;
    }

    public function store(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls']);
        $path = $request->file('file')->getPathname();

        $reader = IOFactory::createReaderForFile($path);
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

            if (! $matched) continue;

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

        if (!$sheetName || empty($headers)) {
            return response()->json(['status' => false, 'message' => 'Format file tidak dikenali (Header baris 6 tidak ditemukan)'], 422);
        }

        $normalized = array_values($headers);
        $headerHash = hash('sha256', json_encode($normalized));

        $existingSchema = InvoiceSchemaPendapatan::where('header_hash', $headerHash)->first();

        $sheet = $spreadsheet->getSheetByName($sheetName);

        $dateColumns = $this->detectDateColumns($sheet, $headers, $dataStart);

        $fromRaw = $sheet->getCell('B2')->getValue();
        $toRaw   = $sheet->getCell('C2')->getValue();

        $fromDate = $fromRaw ? (is_numeric($fromRaw) ? Date::excelToDateTimeObject($fromRaw)->format('Y-m-d') : date('Y-m-d', strtotime($fromRaw))) : date('Y-m-d');
        $toDate = $toRaw ? (is_numeric($toRaw) ? Date::excelToDateTimeObject($toRaw)->format('Y-m-d') : date('Y-m-d', strtotime($toRaw))) : date('Y-m-d');

        return response()->json([
            'status'       => true,
            'headers'      => $headers,
            'header_hash'  => $headerHash,
            'schema_id'    => $existingSchema ? $existingSchema->id : null,
            'date_columns' => $dateColumns,
            'from_date'    => $fromDate,
            'to_date'      => $toDate,
        ]);
    }

    public function process(Request $request)
    {
        $request->validate([
            'file'        => 'required|mimes:xlsx,xls',
            'columns'     => 'required|array',
            'seller_id'   => 'required',
            'date_column' => 'required|string',
            'from_date'   => 'required|date',
            'to_date'     => 'required|date',
            'header_hash' => 'required'
        ]);

        try {
            $path = $request->file('file')->getPathname();
            $spreadsheet = IOFactory::createReaderForFile($path)->load($path);

            $noPesananColLetter = array_search('No. Pesanan', $request->columns);
            if (!$noPesananColLetter) {
                return response()->json(['status' => false, 'message' => 'Kolom "No. Pesanan" tidak ditemukan'], 422);
            }

            $existingSchema = InvoiceSchemaPendapatan::where('header_hash', $request->header_hash)->first();

            $existingFinal = ShopeePendapatan::pluck('no_pesanan')->toArray();
            $existingInQueue = InvoiceDataPendapatan::pluck('payload')
                ->flatMap(fn($p) => collect($p['rows'])->pluck('No. Pesanan'))
                ->toArray();

            $allExisting = collect(array_merge($existingFinal, $existingInQueue))
                ->map(fn($val) => strtoupper(trim((string)$val)))
                ->unique()
                ->flip()
                ->toArray();

            DB::beginTransaction();

            $file = InvoiceFilePendapatan::create([
                'id'           => (string) Str::uuid(),
                'seller_id'    => $request->seller_id,
                'schema_id'    => $existingSchema ? $existingSchema->id : null,
                'header_hash'  => $request->header_hash,
                'uploaded_at'  => now(),
                'from_date'    => $request->from_date,
                'to_date'      => $request->to_date,
                'total_rows'   => 0,
                'processed_at' => null,
            ]);

            $buffer = [];
            $chunk = 0;
            $total = 0;

            foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
                if (stripos($sheet->getTitle(), 'income') === false && stripos($sheet->getTitle(), 'penghasilan') === false) continue;

                $highestRow = $sheet->getHighestRow();
                for ($row = 7; $row <= $highestRow; $row++) {
                    $valNoPesanan = $sheet->getCell($noPesananColLetter . $row)->getFormattedValue();
                    $noPesanan = strtoupper(trim((string)$valNoPesanan));

                    if ($noPesanan === '' || isset($allExisting[$noPesanan])) continue;

                    $rawDate = $sheet->getCell($request->date_column . $row)->getValue();
                    $rowDate = is_numeric($rawDate) ? Date::excelToDateTimeObject($rawDate)->format('Y-m-d') : date('Y-m-d', strtotime($rawDate));

                    if ($rowDate < $request->from_date || $rowDate > $request->to_date) continue;

                    $item = [];
                    foreach ($request->columns as $colLetter => $headerLabel) {
                        $item[$headerLabel] = (string)$sheet->getCell($colLetter . $row)->getFormattedValue();
                    }

                    $buffer[] = $item;
                    $total++;
                    $allExisting[$noPesanan] = true;

                    if (count($buffer) === 500) {
                        InvoiceDataPendapatan::create([
                            'invoice_file_pendapatan_id' => $file->id,
                            'chunk_index' => $chunk++,
                            'payload' => ['rows' => $buffer],
                        ]);
                        $buffer = [];
                    }
                }
                break;
            }

            if (!empty($buffer)) {
                InvoiceDataPendapatan::create([
                    'invoice_file_pendapatan_id' => $file->id,
                    'chunk_index' => $chunk,
                    'payload' => ['rows' => $buffer],
                ]);
            }

            if ($total === 0) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'Semua data sudah ada di sistem.'], 422);
            }

            $file->update(['total_rows' => $total]);
            DB::commit();

            return response()->json([
                'status'   => true,
                'message'  => "Ditemukan $total data baru.",
                'redirect' => url("/admin-panel/shopee/pendapatan/{$file->id}/show")
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        $file = InvoiceFilePendapatan::with(['seller.platform', 'schema', 'chunks'])->findOrFail($id);
        $firstChunk = $file->chunks->first();

        $needMapping = is_null($file->processed_at) &&
            (is_null($file->schema_id) || empty($file->schema?->columns_mapping));

        $dbColumns = collect(DB::getSchemaBuilder()->getColumnListing('shopee_pendapatan'))
            ->reject(fn($c) => in_array($c, [
                'id',
                'uuid',
                'created_at',
                'updated_at',
                'nama_seller',
                'harga_modal',
                'seller_id',
                'invoice_file_id'
            ]))
            ->values();

        return view('pages.modules.transaction.shopee.pendapatan.show', [
            'file'           => $file,
            'rows'           => collect(data_get($firstChunk?->payload, 'rows', []))->take(20),
            'chunkCount'     => $file->chunks->count(),
            'dbColumns'      => $dbColumns,
            'needMapping'    => $needMapping,
            'prefillMapping' => $file->schema?->columns_mapping ?? [],
        ]);
    }

    public function processDatabase(Request $request, $fileId)
    {
        $file = InvoiceFilePendapatan::findOrFail($fileId);

        $mapping = $request->input('mapping');

        if (!$mapping && $file->schema_id) {
            $mapping = $file->schema->columns_mapping;
        }

        if (!$mapping) {
            return back()->with('error', 'Mapping kolom tidak tersedia. Silakan lakukan mapping manual.');
        }

        $nama_seller = $request->nama_seller;

        DB::beginTransaction();
        try {
            $schema = InvoiceSchemaPendapatan::updateOrCreate(
                ['header_hash' => $file->header_hash],
                [
                    'columns_mapping' => $mapping
                ]
            );

            $file->update(['schema_id' => $schema->id]);

            $chunks = InvoiceDataPendapatan::where('invoice_file_pendapatan_id', $fileId)
                ->orderBy('chunk_index', 'asc')
                ->get();

            foreach ($chunks as $chunk) {
                $rows = $chunk->payload['rows'] ?? [];
                foreach ($rows as $row) {
                    $headerNoPesanan = $mapping['no_pesanan'] ?? 'No. Pesanan';
                    $noPesanan = trim((string)($row[$headerNoPesanan] ?? ''));

                    if (empty($noPesanan)) continue;

                    $saveData = [
                        'seller_id'       => $file->seller_id,
                        'invoice_file_id' => $file->id,
                        'nama_seller'     => $nama_seller
                    ];

                    foreach ($mapping as $dbColumn => $excelHeader) {
                        if (empty($excelHeader)) continue;

                        $value = $row[$excelHeader] ?? null;

                        if (in_array($dbColumn, ['total_penghasilan', 'biaya_admin', 'biaya_layanan', 'biaya_proses'])) {
                            $saveData[$dbColumn] = $this->parseNumber($value);
                        } else {
                            $saveData[$dbColumn] = $value;
                        }
                    }

                    ShopeePendapatan::updateOrCreate(['no_pesanan' => $noPesanan], $saveData);
                }
            }

            $file->update(['processed_at' => now()]);
            DB::commit();

            return redirect('/admin-panel/shopee/pendapatan')->with('success', 'Import selesai dan Schema berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses database: ' . $e->getMessage());
        }
    }

    public function parseNumber($value)
    {
        if (is_numeric($value)) return $value;
        $clean = preg_replace('/[^-0-9,.]/', '', $value);
        return (float) str_replace(',', '.', $clean);
    }

    public function kelola(Request $request)
    {
        try {

            DB::beginTransaction();

            $data["seller"] = $this->seller_service->list();

            $query = ShopeePendapatan::query();

            $filterBy = $request->filter_by;
            $dari     = $request->dari;
            $sampai   = $request->sampai;
            $nama_seller = $request->nama_seller;

            $allowedColumns = [
                'waktu_pesanan',
                'tanggal_dana_dilepaskan',
            ];

            if ($nama_seller) {
                $query->where('nama_seller', $nama_seller);
            }

            if (in_array($filterBy, $allowedColumns) && $sampai) {
                $query->whereBetween($filterBy, [
                    $dari . ' 00:00:00',
                    $sampai . ' 23:59:59'
                ]);
            }

            $data["kelola"] = $query->orderBy('created_at', 'desc')->get();

            DB::commit();

            return view("pages.modules.transaction.shopee.pendapatan.kelola", $data);
        } catch (\Exception $e) {

            DB::rollBack();

            dd($e->getMessage());
        }
    }

    public function detail($uuid)
    {
        try {

            DB::beginTransaction();

            $data["detail"] = ShopeePendapatan::where("uuid", $uuid)->first();

            if (empty($data["detail"])) return redirect()->to("/admin-panel/shopee/pendapatan/data")->with("error", "Data Tidak Ditemukan");

            DB::commit();

            return view("pages.modules.transaction.shopee.pendapatan.detail", $data);
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->to("/admin-panel/shopee/pendapatan/data")->with("error", $e->getMessage());
        }
    }
}
