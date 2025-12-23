<?php

namespace App\Http\Controllers\Transaction;

use App\Excel\Pesanan\IncomeHeaderReadFilter;
use App\Http\Controllers\Controller;
use App\Models\InvoiceDataPesanan;
use App\Models\InvoiceFilePesanan;
use App\Models\InvoiceSchemaPesanan;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class PesananController extends Controller
{
    public function index()
    {
        try {

            DB::beginTransaction();

            $data["seller"] = Seller::where("status", "1")->get();

            DB::commit();

            return view("pages.pesanan.upload", $data);
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
        $reader->setReadFilter(new IncomeHeaderReadFilter());
        $spreadsheet = $reader->load($path);

        $headers = [];
        $sheetName = null;
        $headerRowIndex = 1;
        $startDataRow = 2;

        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            if (stripos($sheet->getTitle(), 'orders') === false) continue;

            $sheetName = $sheet->getTitle();
            $highestColumnIndex = Coordinate::columnIndexFromString($sheet->getHighestColumn());

            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $colLetter = Coordinate::stringFromColumnIndex($col);
                $value = trim((string)$sheet->getCell($colLetter . $headerRowIndex)->getValue());
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

        // 🔒 NORMALISASI HEADER SEBELUM HASH
        $normalizedHeaders = array_map(
            fn($h) => strtoupper(trim(preg_replace('/\s+/u', ' ', $h))),
            array_values($headers)
        );
        $headerHash = hash('sha256', json_encode($normalizedHeaders));

        $schema = InvoiceSchemaPesanan::firstOrCreate(
            ['hash' => $headerHash],
            ['headers' => $headers]
        );

        return response()->json([
            'status'            => true,
            'sheetName'         => $sheetName,
            'headers'           => $headers,          // {A: "No. Pesanan", ...}
            'header_hash'       => $headerHash,
            'schema_id'         => $schema->id,
            // 🔴 WAJIB DIPILIH FE
            'required_columns'  => ['No. Pesanan', 'Nomor Referensi SKU']
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

        preg_match_all('/([0-9][0-9.,]*)/', $value, $matches);

        if (empty($matches[1])) {
            return $value;
        }

        $number = end($matches[1]);

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

        // Kalau sudah numeric
        if (is_int($value) || is_float($value)) {
            return $value;
        }

        if (!is_string($value)) {
            return $value;
        }

        $value = trim($value);

        // Hapus semua karakter kecuali digit, titik, koma, minus
        $cleaned = preg_replace('/[^\d.,-]/', '', $value);

        if ($cleaned === '') return null;

        // Ubah format: hapus titik ribuan, ubah koma menjadi titik
        $cleaned = str_replace('.', '', $cleaned);
        $cleaned = str_replace(',', '.', $cleaned);

        // Jika numeric, kembalikan integer atau float
        if (is_numeric($cleaned)) {
            // Bisa dibulatkan ke integer
            return (int) round(floatval($cleaned));
        }

        return null; // kalau tetap bukan numeric
    }

    public function makeKey(string $orderNo, string $sku): string
{
    return strtoupper(trim(preg_replace('/\s+/u',' ', $orderNo)))
         . '|'
         . strtoupper(trim(preg_replace('/\s+/u',' ', $sku)));
}

    public function process(Request $request)
{
    $request->validate([
        'file'      => 'required|mimes:xlsx,xls',
        'columns'   => 'required|array', // columns[A]=No. Pesanan
        'seller_id' => 'required',
        'schema_id' => 'required'
    ]);

    // ===============================
    // NORMALISASI KOLOM DARI FE
    // ===============================
    $selectedHeaders = array_values($request->columns);

    foreach (['No. Pesanan', 'Nomor Referensi SKU'] as $req) {
        if (!in_array($req, $selectedHeaders)) {
            abort(422, "Kolom wajib '{$req}' belum dipilih");
        }
    }

    $path = $request->file('file')->getPathname();
    $reader = IOFactory::createReaderForFile($path);
    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load($path);

    $headerRow  = 1;
    $dataStart = 2;
    $chunkSize = 500;

    DB::beginTransaction();

    try {

        /* =====================================================
         * A) LOAD DATA LAMA → MAP (BERDASARKAN KEY)
         * ===================================================== */
        $rowsMap = [];

        $oldChunks = InvoiceDataPesanan::whereHas(
            'file',
            fn ($q) => $q->where('seller_id', $request->seller_id)
                         ->where('schema_id', $request->schema_id)
        )->get();

        foreach ($oldChunks as $chunk) {
            foreach (($chunk->payload['rows'] ?? []) as $row) {
                if (empty($row['No. Pesanan']) || empty($row['Nomor Referensi SKU'])) {
                    continue;
                }

                $key = $this->makeKey($row['No. Pesanan'], $row['Nomor Referensi SKU']);
                $rowsMap[$key] = $row;
            }
        }

        /* =====================================================
         * B) PROSES EXCEL → INSERT / UPDATE KE MAP
         * ===================================================== */
        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            if (stripos($sheet->getTitle(), 'orders') === false) continue;

            $highestColumn = Coordinate::columnIndexFromString($sheet->getHighestColumn());
            $headerMap = [];

            for ($col = 1; $col <= $highestColumn; $col++) {
                $colLetter = Coordinate::stringFromColumnIndex($col);
                $headerVal = trim((string)$sheet->getCell($colLetter.$headerRow)->getValue());
                if ($headerVal !== '') {
                    $headerMap[$headerVal] = $colLetter;
                }
            }

            foreach ($selectedHeaders as $h) {
                if (!isset($headerMap[$h])) {
                    abort(422, "Kolom '{$h}' tidak ditemukan di Excel");
                }
            }

            $highestRow = $sheet->getHighestRow();

            for ($row = $dataStart; $row <= $highestRow; $row++) {
                $item = [];

                foreach ($selectedHeaders as $h) {
                    $raw = $sheet->getCell($headerMap[$h].$row)->getValue();
                    $item[$h] = is_numeric($raw)
                        ? $this->smartNumberConvert($raw)
                        : trim((string)$raw);
                }

                if (!array_filter($item)) continue;

                $key = $this->makeKey(
                    $item['No. Pesanan'],
                    $item['Nomor Referensi SKU']
                );

                // INSERT / UPDATE
                $rowsMap[$key] = isset($rowsMap[$key])
                    ? array_merge($rowsMap[$key], $item)
                    : $item;
            }
        }

        /* =====================================================
         * C) CHANGE DETECTION
         * ===================================================== */
        ksort($rowsMap);
        $finalHash = hash('sha256', json_encode($rowsMap));

        $lastFile = InvoiceFilePesanan::where('seller_id', $request->seller_id)
            ->where('schema_id', $request->schema_id)
            ->latest('uploaded_at')
            ->first();

        if ($lastFile && $lastFile->data_hash === $finalHash) {
            DB::rollBack();
            return response()->json([
                'status'   => true,
                'message'  => 'Tidak ada perubahan data',
                'redirect' => url('/admin-panel/pesanan/'.$lastFile->id.'/show')
            ]);
        }

        /* =====================================================
         * D) SIMPAN SNAPSHOT BARU
         * ===================================================== */
        $upload = InvoiceFilePesanan::create([
            'seller_id'   => $request->seller_id,
            'schema_id'   => $request->schema_id,
            'uploaded_at' => now(),
            'total_rows'  => count($rowsMap),
            'data_hash'   => $finalHash
        ]);

        $rows   = array_values($rowsMap);
        $chunks = array_chunk($rows, $chunkSize);

        foreach ($chunks as $i => $rowsChunk) {
            InvoiceDataPesanan::create([
                'invoice_file_pesanan_id' => $upload->id,
                'chunk_index'             => $i,
                'payload'                 => [
                    'headers' => $selectedHeaders,
                    'rows'    => $rowsChunk
                ]
            ]);
        }

        DB::commit();

        return response()->json([
            'status'   => true,
            'preview'  => array_slice($rows, 0, 50),
            'redirect' => url('/admin-panel/pesanan/'.$upload->id.'/show')
        ]);

    } catch (\Throwable $e) {
        DB::rollBack();
        throw $e;
    }
}


    public function previewData(Request $request, InvoiceFilePesanan $upload)
    {
        $perPage = 50;
        $page = max((int) $request->get('page', 1), 1);

        $offset = ($page - 1) * $perPage;
        $limit  = $perPage;

        $rows = [];
        $skipped = 0;

        $chunks = InvoiceDataPesanan::where('uploads_file_id', $upload->id)
            ->orderBy('chunk_index')
            ->cursor();

        foreach ($chunks as $chunk) {

            foreach ($chunk->payload['rows'] as $row) {

                if ($skipped < $offset) {
                    $skipped++;
                    continue;
                }

                if (count($rows) < $limit) {
                    $rows[] = $row;
                }

                if (count($rows) === $limit) {
                    break 2;
                }
            }
        }

        return view('pages.pesanan.preview', [
            'rows'       => $rows,
            'currentPage' => $page,
            'perPage'    => $perPage,
            'total'      => $upload->total_rows,
            'upload'     => $upload
        ]);
    }

    public function show(string $id)
    {
        $file = InvoiceFilePesanan::with([
            'seller.platform',
            'schema',
            'chunks' => function ($q) {
                $q->orderBy('chunk_index');
            }
        ])->findOrFail($id);

        $firstChunk = $file->chunks->first();

        return view('pages.pesanan.show', [
            'file'       => $file,
            'headers'    => $firstChunk?->payload['headers'] ?? [],
            'rows'       => collect($firstChunk?->payload['rows'] ?? [])->take(20),
            'chunkCount' => $file->chunks->count()
        ]);
    }
}
