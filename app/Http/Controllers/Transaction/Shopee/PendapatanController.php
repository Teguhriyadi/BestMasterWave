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
        $spreadsheet = $reader->load($path);

        $headers = [];
        $sheetName = null;
        $headerRow = 6;
        $dataStart = 7;

        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            if (stripos($sheet->getTitle(), 'income') === false) continue;

            $sheetName = $sheet->getTitle();
            $maxCol = Coordinate::columnIndexFromString($sheet->getHighestColumn());

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
                'status' => false,
                'message' => 'Header tidak ditemukan'
            ], 422);
        }

        $sheet = IOFactory::createReaderForFile($path)->load($path)->getSheetByName($sheetName);
        $dateColumns = $this->detectDateColumns($sheet, $headers, $dataStart);

        $fromRaw = $sheet->getCell('B2')->getValue();
        $toRaw   = $sheet->getCell('C2')->getValue();

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

        $normalized = array_values($headers);
        sort($normalized);
        $headerHash = hash('sha256', json_encode($normalized));

        return response()->json([
            'status'       => true,
            'sheetName'    => $sheetName,
            'headers'      => $headers,
            'header_hash'  => $headerHash,
            'date_columns' => $dateColumns,
            'from_date'    => $fromDate,
            'to_date'      => $toDate,
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
        ]);

        $path = $request->file('file')->getPathname();
        $spreadsheet = IOFactory::createReaderForFile($path)->load($path);

        /* ===== ambil kolom No. Pesanan ===== */
        $noPesananCol = array_search('No. Pesanan', $request->columns);
        if (!$noPesananCol) {
            return response()->json([
                'status' => false,
                'message' => 'Kolom "No. Pesanan" tidak ditemukan'
            ], 422);
        }

        /* ===== ambil semua no pesanan dari excel ===== */
        $excelOrders = [];

        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            if (stripos($sheet->getTitle(), 'income') === false) continue;

            for ($row = 7; $row <= $sheet->getHighestRow(); $row++) {
                $val = $sheet->getCell($noPesananCol . $row)->getValue();
                if ($val) $excelOrders[] = (string) $val;
            }
            break;
        }

        $excelOrders = array_unique($excelOrders);

        if (empty($excelOrders)) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data valid'
            ], 422);
        }

        /* ===== cek data yang sudah ada ===== */
        $existingOrders = ShopeePendapatan::whereIn('no_pesanan', $excelOrders)
            ->pluck('no_pesanan')
            ->toArray();

        $newOrders = array_diff($excelOrders, $existingOrders);

        if (count($newOrders) === 0) {
            return response()->json([
                'status' => false,
                'message' => 'Data sudah pernah di-upload. Tidak ada data baru.'
            ], 422);
        }

        /* ===== AUTO SCHEMA ===== */
        $excelHeaders = array_values($request->columns);
        sort($excelHeaders);
        $hash = hash('sha256', json_encode($excelHeaders));

        $schema = InvoiceSchemaPendapatan::firstOrCreate(
            ['hash' => $hash],
            ['headers' => $this->normalizeSchemaHeaders($request->columns)]
        );

        DB::beginTransaction();

        try {
            $file = InvoiceFilePendapatan::create([
                'seller_id'   => $request->seller_id,
                'uploaded_at' => now(),
                'from_date'   => $request->from_date,
                'to_date'     => $request->to_date,
                'total_rows'  => 0,
                'schema_id'   => $schema->id,
            ]);

            $buffer = [];
            $chunk  = 0;
            $total  = 0;

            foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
                if (stripos($sheet->getTitle(), 'income') === false) continue;

                for ($row = 7; $row <= $sheet->getHighestRow(); $row++) {

                    $rawDate = $sheet->getCell($request->date_column . $row)->getValue();
                    if (!$rawDate) continue;

                    $rowDate = is_numeric($rawDate)
                        ? ExcelDate::excelToDateTimeObject($rawDate)->format('Y-m-d')
                        : date('Y-m-d', strtotime($rawDate));

                    if ($rowDate < $request->from_date || $rowDate > $request->to_date) continue;

                    $item = [];
                    foreach ($request->columns as $col => $header) {
                        $item[$header] = $sheet->getCell($col . $row)->getValue();
                    }

                    $noPesanan = $item['No. Pesanan'] ?? null;
                    if (!$noPesanan || !in_array($noPesanan, $newOrders)) continue;

                    $buffer[] = $item;
                    $total++;

                    if (count($buffer) === 500) {
                        InvoiceDataPendapatan::create([
                            'invoice_file_pendapatan_id' => $file->id,
                            'chunk_index' => $chunk++,
                            'payload' => ['rows' => $buffer],
                        ]);
                        $buffer = [];
                    }
                }
            }

            if ($buffer) {
                InvoiceDataPendapatan::create([
                    'invoice_file_pendapatan_id' => $file->id,
                    'chunk_index' => $chunk,
                    'payload' => ['rows' => $buffer],
                ]);
            }

            $file->update(['total_rows' => $total]);

            DB::commit();

            return response()->json([
                'status'   => true,
                'message'  => 'Berhasil menambahkan ' . count($newOrders) . ' data baru',
                'redirect' => url("/admin-panel/shopee/pendapatan/{$file->id}/show")
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show(string $id)
    {
        $file = InvoiceFilePendapatan::with([
            'seller.platform',
            'schema',
            'chunks'
        ])->findOrFail($id);

        $firstChunk = $file->chunks->first();

        // WAJIB ADA
        $newRowsCount = 0;

        if ($firstChunk && $file->schema) {

            $mapping = $file->schema->headers;
            $excelKeyNoPesanan = $mapping['no_pesanan'] ?? null;

            if ($excelKeyNoPesanan) {
                $existingOrders = ShopeePendapatan::pluck('no_pesanan')->flip();

                foreach ($file->chunks as $chunk) {
                    foreach ($chunk->payload['rows'] as $row) {
                        if (!isset($row[$excelKeyNoPesanan])) continue;

                        if (!isset($existingOrders[(string)$row[$excelKeyNoPesanan]])) {
                            $newRowsCount++;
                        }
                    }
                }
            }
        }

        $dbColumns = collect(
            Schema::getColumnListing('shopee_pendapatan')
        )->reject(
            fn($c) =>
            in_array($c, ['id', 'uuid', 'created_at', 'updated_at', 'harga_modal', 'nama_seller'])
        )->values();

        return view('pages.pendapatan.show', [
            'file'           => $file,
            'rows'           => collect(data_get($firstChunk?->payload, 'rows', []))->take(20),
            'chunkCount'     => $file->chunks->count(),
            'dbColumns'      => $dbColumns,
            'needMapping'    => false,
            'prefillMapping' => $file->schema?->headers ?? [],
            'newRowsCount'   => $newRowsCount,
        ]);
    }

    public function normalizeSchemaHeaders(array $columns): array
    {
        return [
            'no_pesanan'              => $columns[array_search('No. Pesanan', $columns)],
            'tanggal_dana_dilepaskan' => $columns[array_search('Tanggal Dana Dilepaskan', $columns)],
            'username'                => $columns[array_search('Username (Pembeli)', $columns)],
            'total_penghasilan'       => $columns[array_search('Total Penghasilan', $columns)],
            'premi'                   => $columns[array_search('Premi', $columns)]
        ];
    }

    protected function normalize(string $text): string
    {
        return preg_replace(
            '/[^a-z0-9]/',
            '',
            strtolower(trim($text))
        );
    }

    protected function autoMap(array $excelHeaders, array $dbColumns): array
    {
        $result = [];

        $normalizedExcel = [];
        foreach ($excelHeaders as $excel) {
            $normalizedExcel[$excel] = $this->normalize($excel);
        }

        foreach ($dbColumns as $db) {

            $dbNorm = $this->normalize($db);
            $bestScore = 0;
            $bestExcel = null;

            foreach ($normalizedExcel as $excel => $excelNorm) {

                // 1️⃣ exact
                if ($dbNorm === $excelNorm) {
                    $bestExcel = $excel;
                    break;
                }

                // 2️⃣ fuzzy
                similar_text($dbNorm, $excelNorm, $percent);
                if ($percent > $bestScore) {
                    $bestScore = $percent;
                    $bestExcel = $excel;
                }
            }

            // threshold aman
            if ($bestExcel && $bestScore >= 70) {
                $result[$db] = $bestExcel;
            }
        }

        return $result;
    }

    public function processDatabase(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $file = InvoiceFilePendapatan::with(['chunks', 'schema'])
                ->findOrFail($id);

            /**
             * =================================================
             * 🔑 AMBIL SCHEMA (AUTO / MANUAL)
             * =================================================
             */
            if ($file->schema_id) {
                // AUTO → schema sudah ada
                $mapping = $file->schema->headers;
            } else {
                // MANUAL → pertama kali
                $request->validate([
                    'mapping' => 'required|array'
                ]);

                $mapping = array_filter($request->mapping);

                if (count($mapping) === 0) {
                    throw new \Exception('Mapping belum diisi');
                }

                $hash = hash('sha256', json_encode($mapping));

                $schema = InvoiceSchemaPendapatan::firstOrCreate(
                    ['hash' => $hash],
                    ['headers' => $mapping]
                );

                $file->update([
                    'schema_id' => $schema->id
                ]);
            }

            /**
             * =================================================
             * 🔥 AMBIL no_pesanan EXCEL DARI SCHEMA
             * =================================================
             */
            $excelKeyNoPesanan = $mapping['no_pesanan'] ?? null;

            if (!$excelKeyNoPesanan) {
                throw new \Exception('Mapping kolom No. Pesanan tidak ditemukan');
            }

            // lookup data existing (biar O(1))
            $existingOrders = ShopeePendapatan::pluck('no_pesanan')->flip();

            /**
             * =================================================
             * INSERT KE shopee_pendapatan (HANYA DATA BARU)
             * =================================================
             */
            $inserted = 0;

            foreach ($file->chunks as $chunk) {
                foreach ($chunk->payload['rows'] as $row) {

                    if (!isset($row[$excelKeyNoPesanan])) {
                        continue;
                    }

                    $noPesanan = (string) $row[$excelKeyNoPesanan];

                    // ⛔ skip jika sudah ada
                    if (isset($existingOrders[$noPesanan])) {
                        continue;
                    }

                    $data = [
                        'uuid'       => Str::uuid(),
                        'no_pesanan' => $noPesanan,
                    ];

                    foreach ($mapping as $dbColumn => $excelHeader) {

                        if (!array_key_exists($excelHeader, $row)) {
                            continue;
                        }

                        $value = $row[$excelHeader];

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

                    if (count($data) <= 2) {
                        continue;
                    }

                    ShopeePendapatan::create($data);
                    $existingOrders[$noPesanan] = true;
                    $inserted++;
                }
            }

            DB::commit();

            return redirect()
                ->to('/admin-panel/shopee/pendapatan/kelola-data')
                ->with('success', "Berhasil memproses {$inserted} data baru");
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
