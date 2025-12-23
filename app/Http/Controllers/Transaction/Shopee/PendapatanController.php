<?php

namespace App\Http\Controllers\Transaction\Shopee;

use App\Excel\Pendapatan\IncomeDataReadFilter;
use App\Excel\Pendapatan\IncomeHeaderReadFilter as PendapatanIncomeHeaderReadFilter;
use App\Http\Controllers\Controller;
use App\Models\InvoiceDataPendapatan;
use App\Models\InvoiceFilePendapatan;
use App\Models\InvoiceSchemaPendapatan;
use App\Models\Platform;
use App\Models\Seller;
use App\Models\ShopeePendapatan;
use App\Pendapatan\Excel\IncomeHeaderReadFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Support\Str;

class PendapatanController extends Controller
{
    public function index()
    {
        try {

            DB::beginTransaction();

            $platform = Platform::where("slug", "shopee")->first();
            $data["seller"] = Seller::where("status", "1")
                ->where("platform_id", $platform->id)->get();

            DB::commit();

            return view("pages.pendapatan.upload", $data);
        } catch (\Exception $e) {

            DB::rollBack();

            dd($e->getMessage());
        }
    }

    public function detectDateColumns($sheet, $headers, $startRow = 7)
    {
        $dateColumns = [];

        foreach ($headers as $colLetter => $headerName) {

            $checked = 0;
            $dateHit = 0;

            for ($row = $startRow; $row < $startRow + 10; $row++) {

                $cell = $sheet->getCell($colLetter . $row);
                $value = $cell->getValue();

                if ($value === null || $value === '') {
                    continue;
                }

                $checked++;

                // 1️⃣ Excel real date (numeric + date format)
                if (ExcelDate::isDateTime($cell)) {
                    $dateHit++;
                    continue;
                }

                // 2️⃣ String yang bisa diparse jadi tanggal
                if (is_string($value) && strtotime($value) !== false) {
                    $dateHit++;
                    continue;
                }
            }

            if ($checked > 0 && ($dateHit / $checked) >= 0.6) {
                $dateColumns[$colLetter] = $headerName;
            }
        }

        return $dateColumns;
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $path = $request->file('file')->getPathname();

        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);
        $reader->setReadFilter(new PendapatanIncomeHeaderReadFilter());

        $spreadsheet = $reader->load($path);

        $headers = [];
        $sheetName = null;
        $headerRowIndex = 6;
        $startDataRow = 7;

        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {

            if (stripos($sheet->getTitle(), 'income') === false) {
                continue;
            }

            $sheetName = $sheet->getTitle();

            $highestColumnIndex = Coordinate::columnIndexFromString(
                $sheet->getHighestColumn()
            );

            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $colLetter = Coordinate::stringFromColumnIndex($col);
                $value = trim((string) $sheet->getCell($colLetter . $headerRowIndex)->getValue());

                if ($value !== '') {
                    $headers[$colLetter] = $value;
                }
            }

            break;
        }

        if (!$sheetName || empty($headers)) {
            return response()->json([
                'status' => false,
                'message' => 'Header tidak ditemukan'
            ], 422);
        }

        // Normalisasi header → hash
        $normalizedHeaders = array_values($headers);
        $headerHash = hash('sha256', json_encode($normalizedHeaders));

        // 🔥 CEK SCHEMA EXIST / CREATE BARU
        $schema = InvoiceSchemaPendapatan::firstOrCreate(
            ['hash' => $headerHash],
            ['headers' => $headers]
        );

        $dataReader = IOFactory::createReaderForFile($path);
        $dataReader->setReadDataOnly(true);

        $dataSpreadsheet = $dataReader->load($path);
        $dataSheet = $dataSpreadsheet->getSheetByName($sheetName);

        $fromRaw = $dataSheet->getCell('B2')->getValue();
        $toRaw   = $dataSheet->getCell('C2')->getValue();

        $fromDate = $fromRaw
            ? (is_numeric($fromRaw)
                ? ExcelDate::excelToDateTimeObject($fromRaw)->format('Y-m-d')
                : date('Y-m-d', strtotime($fromRaw)))
            : null;

        $toDate = $toRaw
            ? (is_numeric($toRaw)
                ? ExcelDate::excelToDateTimeObject($toRaw)->format('Y-m-d')
                : date('Y-m-d', strtotime($toRaw)))
            : null;

        $dateColumns = $this->detectDateColumns(
            $dataSheet,
            $headers,
            $startDataRow
        );

        return response()->json([
            'status'        => true,
            'sheetName'     => $sheetName,
            'headers'       => $headers,
            'header_hash'   => $headerHash,
            'schema_exists' => $schema->wasRecentlyCreated ? false : true,
            'schema_id'     => $schema->id,
            'date_columns'  => $dateColumns,
            'from_date'     => $fromDate,
            'to_date'       => $toDate
        ]);
    }

    public function detectCellType($cell): string
    {
        if ($cell === null || $cell === '') {
            return 'empty';
        }

        if ($cell instanceof \PhpOffice\PhpSpreadsheet\Cell\Cell) {
            $value = $cell->getValue();

            if ($cell->isFormula()) {
                return 'formula';
            }

            if (ExcelDate::isDateTime($cell)) {
                return 'date';
            }

            if (is_numeric($value)) {
                return 'number';
            }

            if (is_bool($value)) {
                return 'boolean';
            }

            return 'string';
        }

        if (is_numeric($cell)) {
            return 'number';
        }

        return 'string';
    }

    public function detectColumnTypes($sheet, array $headers, int $startRow = 7, int $sample = 10)
    {
        $types = [];

        foreach ($headers as $col => $header) {

            $count = [
                'number' => 0,
                'date'   => 0,
                'string' => 0,
            ];

            $checked = 0;

            for ($row = $startRow; $row <= $sheet->getHighestRow(); $row++) {

                $cell = $sheet->getCell($col . $row);
                $value = $cell->getValue();

                if ($value === null || $value === '') continue;

                if (ExcelDate::isDateTime($cell)) {
                    $count['date']++;
                } elseif (is_numeric($value)) {
                    $count['number']++;
                } else {
                    $count['string']++;
                }

                if (++$checked >= $sample) break;
            }

            // 🔥 mayoritas menang
            arsort($count);
            $types[$col] = array_key_first($count);
        }

        return $types;
    }

    public function normalizeValue($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Jika sudah numeric dari Excel
        if (is_int($value) || is_float($value)) {
            return $value;
        }

        if (!is_string($value)) {
            return $value;
        }

        $value = trim($value);

        /**
         * 🔥 AMBIL ANGKA TERAKHIR
         * Contoh:
         * "2511063XJVEUGY  15.000" → "15.000"
         * "ABC 9046"              → "9046"
         */
        preg_match_all('/([0-9][0-9.,]*)/', $value, $matches);

        if (empty($matches[1])) {
            // Tidak ada angka sama sekali → biarkan string
            return $value;
        }

        // Ambil angka terakhir
        $number = end($matches[1]);

        // Normalisasi format angka
        $clean = str_replace('.', '', $number);
        $clean = str_replace(',', '.', $clean);

        return is_numeric($clean) ? $clean + 0 : $value;
    }

    public function detectNormalizedType($value): string
    {
        if ($value === null) return 'empty';

        if (is_int($value) || is_float($value)) {
            return 'number';
        }

        if ($value instanceof \DateTimeInterface) {
            return 'date';
        }

        return 'string';
    }

    public function detectColumnTypesFromData(
        $sheet,
        array $columns,
        int $startRow,
        int $sample = 20
    ): array {
        $stats = [];

        foreach ($columns as $col => $header) {
            $stats[$header] = [
                'number' => 0,
                'string' => 0
            ];

            $checked = 0;

            for ($row = $startRow; $row <= $sheet->getHighestRow(); $row++) {

                $raw = $sheet->getCell($col . $row)->getValue();
                $value = $this->normalizeValue($raw);

                $type = $this->detectNormalizedType($value);

                if ($type !== 'empty') {
                    $stats[$header][$type]++;
                }

                if (++$checked >= $sample) break;
            }
        }

        // 🔥 mayoritas menang
        $final = [];
        foreach ($stats as $header => $count) {
            $final[$header] =
                $count['number'] >= $count['string']
                ? 'number'
                : 'string';
        }

        return $final;
    }

    public function smartNumberConvert($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Sudah numeric → biarkan
        if (is_int($value) || is_float($value)) {
            return $value;
        }

        if (!is_string($value)) {
            return $value;
        }

        /**
         * Ambil ANGKA TERAKHIR dari string
         * "ABC 15.000" → 15.000
         * "ABC 9046"   → 9046
         */
        preg_match_all('/([0-9][0-9.,]*)/', $value, $matches);

        if (empty($matches[1])) {
            return $value;
        }

        $number = end($matches[1]);

        // Normalisasi
        $number = str_replace('.', '', $number);
        $number = str_replace(',', '.', $number);

        return is_numeric($number) ? $number + 0 : $value;
    }

    public function smartValue($value)
    {
        if ($value === null) return null;

        if (is_numeric($value)) {
            return (float) $value;
        }

        if (is_string($value)) {
            $clean = str_replace(['.', ',', 'Rp', ' '], ['', '.', '', ''], trim($value));

            if (is_numeric($clean)) {
                return (float) $clean;
            }
        }

        return trim((string) $value);
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
            'schema_id'   => 'nullable',
            'header_hash' => 'nullable|string',
        ]);

        $path = $request->file('file')->getPathname();

        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);
        $reader->setReadFilter(new IncomeDataReadFilter());
        $spreadsheet = $reader->load($path);

        $dataStartRow = 7;
        $chunkSize = 500;

        DB::beginTransaction();

        try {

            /* =============================
           VALIDASI & AMBIL SCHEMA
        ============================= */
            $schemaId = $request->schema_id;

            if (!$schemaId && $request->header_hash) {
                $schema = InvoiceSchemaPendapatan::where('hash', $request->header_hash)->first();
                $schemaId = $schema?->id;
            }

            if (!$schemaId) {
                throw new \Exception('Schema ID tidak ditemukan.');
            }

            /* =============================
           AMBIL ROW YANG SUDAH ADA (ANTI DUPLIKAT)
        ============================= */
            $existingRows = InvoiceDataPendapatan::whereHas('file', function ($q) use ($request, $schemaId) {
                $q->where('seller_id', $request->seller_id)
                    ->where('schema_id', $schemaId);
            })
                ->pluck('payload')
                ->flatMap(fn($payload) => collect($payload['rows'] ?? []))
                ->toArray();

            /* =============================
           BUAT FILE UPLOAD BARU
        ============================= */
            $upload = InvoiceFilePendapatan::create([
                'seller_id'   => $request->seller_id,
                'uploaded_at' => now(),
                'from_date'   => $request->from_date,
                'to_date'     => $request->to_date,
                'total_rows'  => 0,
                'schema_id'   => $schemaId,
            ]);

            $buffer = [];
            $totalRows = 0;
            $chunkIndex = 0;

            /* =============================
           LOOP DATA EXCEL
        ============================= */
            foreach ($spreadsheet->getWorksheetIterator() as $sheet) {

                if (stripos($sheet->getTitle(), 'income') === false) {
                    continue;
                }

                $highestRow = $sheet->getHighestRow();

                for ($row = $dataStartRow; $row <= $highestRow; $row++) {

                    /* ===== PARSE & FILTER TANGGAL ===== */
                    $rawDate = $sheet->getCell($request->date_column . $row)->getValue();
                    if (!$rawDate) continue;

                    $rowDate = is_numeric($rawDate)
                        ? ExcelDate::excelToDateTimeObject($rawDate)->format('Y-m-d')
                        : date('Y-m-d', strtotime($rawDate));

                    if ($rowDate < $request->from_date || $rowDate > $request->to_date) {
                        continue;
                    }

                    /* ===== AMBIL SEMUA KOLOM ===== */
                    $item = [];

                    foreach ($request->columns as $col => $header) {
                        $raw = $sheet->getCell($col . $row)->getValue();
                        $item[$header] = $this->smartValue($raw);
                    }

                    if (!array_filter($item)) {
                        continue;
                    }

                    $item['_date'] = $rowDate;

                    /* ===== CEK DUPLIKAT ===== */
                    $exists = false;
                    foreach ($existingRows as $existing) {
                        if ($existing == $item) {
                            $exists = true;
                            break;
                        }
                    }

                    if ($exists) continue;

                    $existingRows[] = $item;
                    $buffer[] = $item;
                    $totalRows++;

                    /* ===== SIMPAN PER CHUNK ===== */
                    if (count($buffer) === $chunkSize) {
                        InvoiceDataPendapatan::create([
                            'invoice_file_pendapatan_id' => $upload->id,
                            'chunk_index' => $chunkIndex++,
                            'payload' => [
                                'headers' => array_values($request->columns),
                                'rows'    => $buffer,
                            ],
                        ]);
                        $buffer = [];
                    }
                }
            }

            /* =============================
           SISA BUFFER
        ============================= */
            if (!empty($buffer)) {
                InvoiceDataPendapatan::create([
                    'invoice_file_pendapatan_id' => $upload->id,
                    'chunk_index' => $chunkIndex,
                    'payload' => [
                        'headers' => array_values($request->columns),
                        'rows'    => $buffer,
                    ],
                ]);
            }

            $upload->update(['total_rows' => $totalRows]);

            DB::commit();

            return response()->json([
                'status'   => true,
                'redirect' => url('/admin-panel/shopee/pendapatan/' . $upload->id . '/show'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show(string $id)
    {
        try {

            DB::beginTransaction();

            $file = InvoiceFilePendapatan::with([
                'seller.platform',
                'schema',
                'chunks' => function ($q) {
                    $q->orderBy('chunk_index');
                }
            ])->findOrFail($id);

            $firstChunk = $file->chunks->first();

            $dbColumns = collect(Schema::getColumnListing('shopee_pendapatan'))
                ->reject(fn($c) => in_array($c, ['id', 'nama_seller', 'uuid', 'created_at', 'updated_at']))
                ->values();

            DB::commit();

            return view('pages.pendapatan.show', [
                'file'       => $file,
                'headers'    => $firstChunk?->payload['headers'] ?? [],
                'rows'       => collect($firstChunk?->payload['rows'] ?? [])->take(20),
                'chunkCount' => $file->chunks->count(),
                'dbColumns'  => $dbColumns,
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            dd($e->getMessage());
        }
    }

    public function processDatabase(Request $request, $id)
    {
        $request->validate([
            'mapping' => 'required|array'
        ]);

        $mapping = array_filter($request->mapping);

        DB::beginTransaction();

        try {

            $file = InvoiceFilePendapatan::with('chunks')->findOrFail($id);

            foreach ($file->chunks as $chunk) {

                foreach ($chunk->payload['rows'] as $row) {

                    $data = [
                        'uuid' => Str::uuid(),
                    ];

                    foreach ($mapping as $dbColumn => $excelKey) {

                        if (!isset($row[$excelKey])) {
                            continue;
                        }

                        $value = $row[$excelKey];

                        // handle tanggal
                        if (in_array($dbColumn, [
                            'waktu_pesanan',
                            'tanggal_dana_dilepaskan'
                        ])) {
                            $data[$dbColumn] = $value
                                ? date('Y-m-d', strtotime($value))
                                : null;
                            continue;
                        }

                        $data[$dbColumn] = $this->smartValue($value);
                    }

                    if (count($data) <= 1) {
                        continue;
                    }

                    ShopeePendapatan::create($data);
                }
            }

            DB::commit();

            return redirect()->to("/admin-panel/shopee/pendapatan/kelola-data")->with("success", "Data Berhasil di Proses");

        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function kelola()
    {
        try {

            DB::beginTransaction();

            $data["kelola"] = ShopeePendapatan::get();

            DB::commit();

            return view("pages.pendapatan.kelola", $data);
        } catch (\Exception $e) {

            DB::rollBack();

            dd($e->getMessage());
        }
    }
}
