<?php

namespace App\Http\Controllers\Transaction\Shopee;

use App\Excel\Pesanan\IncomeHeaderReadFilter;
use App\Http\Controllers\Controller;
use App\Models\InvoiceDataPesanan;
use App\Models\InvoiceFilePesanan;
use App\Models\InvoiceSchemaPesanan;
use App\Models\Platform;
use App\Models\Seller;
use App\Models\ShopeePesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class PesananController extends Controller
{
    protected array $dateColumns = [
        'Pesanan Harus Dikirimkan Sebelum (Menghindari keterlambatan)',
        'Waktu Pengiriman Diatur',
        'Waktu Pesanan Dibuat',
        'Waktu Pembayaran Dilakukan',
        'Waktu Pesanan Selesai'
    ];

    protected array $forceStringColumns = [
        'No. Pesanan',
        'Nomor Referensi SKU',
        'No. Resi',
    ];

    protected array $dateDatabaseColumns = [
        'pesanan_harus_dikirimkan',
        'waktu_pengiriman_diatur',
        'waktu_pesanan_dibuat',
        'waktu_pembayaran_dilakukan',
        'waktu_pesanan_selesai',
    ];

    protected array $forceStringDatabaseColumns = [
        'no_pesanan',
        'nomor_referensi_sku',
        'no_resi',
    ];

    public function index()
    {
        try {

            DB::beginTransaction();

            $shopee = Platform::where('status', '1')
                ->where('slug', 'shopee')->first();

            $data['seller'] = Seller::where('status', '1')
                ->where('platform_id', $shopee->id)->get();

            DB::commit();

            return view('pages.pesanan.upload', $data);
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
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $path = $request->file('file')->getPathname();

        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($path);

        $headers   = [];
        $sheetName = null;
        $headerRow = 1;

        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {

            if (stripos($sheet->getTitle(), 'orders') === false) {
                continue;
            }

            $sheetName = $sheet->getTitle();
            $highestColumn = Coordinate::columnIndexFromString(
                $sheet->getHighestColumn()
            );

            for ($c = 1; $c <= $highestColumn; $c++) {
                $letter = Coordinate::stringFromColumnIndex($c);

                // 🔥 PAKAI formatted value (lebih aman)
                $header = trim((string) $sheet->getCell($letter . $headerRow)->getFormattedValue());

                if ($header !== '') {
                    // SIMPAN BERURUT (TANPA HURUF SEBAGAI KEY UTAMA)
                    $headers[] = $header;
                }
            }

            break;
        }

        if (!$sheetName || empty($headers)) {
            abort(422, 'Header tidak ditemukan');
        }

        $normalized = array_map(
            fn($h) => strtoupper(trim(preg_replace('/\s+/u', ' ', $h))),
            $headers
        );

        $hash = hash('sha256', json_encode($normalized));

        $schema = InvoiceSchemaPesanan::firstOrCreate(
            ['hash' => $hash],
            [
                'headers' => $headers,
            ]
        );

        return response()->json([
            'status'   => true,
            'sheetName' => $sheetName,
            'headers'  => $headers,
            'schema_id' => $schema->id,
            'required_columns' => [
                'No. Pesanan',
                'Nomor Referensi SKU',
            ],
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
                'date' => 0,
                'string' => 0,
            ];

            $checked = 0;

            for ($row = $startRow; $row <= $sheet->getHighestRow(); $row++) {

                $cell = $sheet->getCell($col . $row);
                $value = $cell->getValue();

                if ($value === null || $value === '') {
                    continue;
                }

                if (ExcelDate::isDateTime($cell)) {
                    $count['date']++;
                } elseif (is_numeric($value)) {
                    $count['number']++;
                } else {
                    $count['string']++;
                }

                if (++$checked >= $sample) {
                    break;
                }
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

        if (! is_string($value)) {
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
        if ($value === null) {
            return 'empty';
        }

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
                'string' => 0,
            ];

            $checked = 0;

            for ($row = $startRow; $row <= $sheet->getHighestRow(); $row++) {

                $raw = $sheet->getCell($col . $row)->getValue();
                $value = $this->normalizeValue($raw);

                $type = $this->detectNormalizedType($value);

                if ($type !== 'empty') {
                    $stats[$header][$type]++;
                }

                if (++$checked >= $sample) {
                    break;
                }
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

        if (! is_string($value)) {
            return $value;
        }

        $value = trim($value);

        // Hapus semua karakter kecuali digit, titik, koma, minus
        $cleaned = preg_replace('/[^\d.,-]/', '', $value);

        if ($cleaned === '') {
            return null;
        }

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

    public function makeKey(string $noPesanan, string $sku): string
    {
        return trim($noPesanan) . '|' . trim($sku);
    }

    public function smartValue($value)
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            $value = trim($value);
            if ($value === '') {
                return null;
            }
        }

        if (is_string($value) && preg_match('/[A-Za-z]/', $value)) {
            return $value;
        }

        if (is_numeric($value)) {
            $len = strlen((string) $value);

            if (in_array($len, [8, 12, 14], true)) {
                return (string) $value;
            }
        }

        if (is_numeric($value)) {
            try {
                $dt = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                if ((int) $dt->format('Y') >= 2000 && (int) $dt->format('Y') <= 2100) {
                    return $dt->format('Y-m-d H:i:s');
                }
            } catch (\Throwable $e) {
            }
        }

        if (is_string($value)) {
            $negative = str_starts_with($value, '-');
            $num = preg_replace('/[^0-9]/', '', $value);

            if ($num !== '') {
                return $negative ? -(int) $num : (int) $num;
            }
        }

        if (is_numeric($value)) {
            return (int) round($value);
        }

        return $value;
    }

    public function smartDateValue($value)
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            $value = trim($value);
            if ($value === '') {
                return null;
            }

            if (preg_match('/^\d{4}-\d{2}-\d{2}/', $value)) {
                return date('Y-m-d H:i:s', strtotime($value));
            }
        }

        if (is_numeric($value) && strlen((string) $value) === 12) {
            $v = (string) $value;

            return sprintf(
                '%s-%s-%s %s:%s:00',
                substr($v, 0, 4),
                substr($v, 4, 2),
                substr($v, 6, 2),
                substr($v, 8, 2),
                substr($v, 10, 2),
            );
        }

        // EXCEL DATE SERIAL
        if (is_numeric($value)) {
            try {
                $dt = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                if ((int) $dt->format('Y') >= 2000) {
                    return $dt->format('Y-m-d H:i:s');
                }
            } catch (\Throwable $e) {
            }
        }

        return null;
    }

    public function smartDateTimeValue($value): ?string
    {
        if ($value === null) {
            return null;
        }

        // string kosong
        if (is_string($value)) {
            $value = trim($value);
            if ($value === '') return null;

            // yyyy-mm-dd hh:mm
            if (preg_match('/^\d{4}-\d{2}-\d{2}/', $value)) {
                return date('Y-m-d H:i:s', strtotime($value));
            }

            // yyyymmddhhmm / yyyymmddhhmmss
            if (preg_match('/^\d{12,14}$/', $value)) {
                return \Carbon\Carbon::createFromFormat(
                    strlen($value) === 12 ? 'YmdHi' : 'YmdHis',
                    $value
                )->format('Y-m-d H:i:s');
            }
        }

        // excel serial
        if (is_numeric($value)) {
            try {
                $dt = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                if ((int) $dt->format('Y') >= 2000 && (int) $dt->format('Y') <= 2100) {
                    return $dt->format('Y-m-d H:i:s');
                }
            } catch (\Throwable $e) {
            }
        }

        return null;
    }

    public function process(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'columns' => 'required|array',
            'seller_id' => 'required',
            'schema_id' => 'required',
        ]);

        $selectedHeaders = array_values($request->columns);

        foreach (['No. Pesanan', 'Nomor Referensi SKU'] as $req) {
            if (! in_array($req, $selectedHeaders, true)) {
                abort(422, "Kolom wajib '{$req}' belum dipilih");
            }
        }

        $path = $request->file('file')->getPathname();
        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($path);

        $headerRow = 1;
        $dataStart = 2;
        $chunkSize = 500;

        DB::beginTransaction();

        try {

            /* ===============================
         * A) LOAD DATA LAMA (MAP)
         * =============================== */
            $rowsMap = [];

            $oldChunks = InvoiceDataPesanan::whereHas(
                'file',
                fn($q) => $q->where('seller_id', $request->seller_id)
                    ->where('schema_id', $request->schema_id)
            )->get();

            foreach ($oldChunks as $chunk) {
                foreach ($chunk->payload['rows'] ?? [] as $row) {
                    if (! empty($row['No. Pesanan']) && ! empty($row['Nomor Referensi SKU'])) {
                        $rowsMap[$this->makeKey($row['No. Pesanan'], $row['Nomor Referensi SKU'])] = $row;
                    }
                }
            }

            /* ===============================
         * B) BACA EXCEL → UPDATE MAP
         * =============================== */
            foreach ($spreadsheet->getWorksheetIterator() as $sheet) {

                if (stripos($sheet->getTitle(), 'orders') === false) {
                    continue;
                }

                $highestColumn = Coordinate::columnIndexFromString($sheet->getHighestColumn());
                $headerMap = [];

                for ($c = 1; $c <= $highestColumn; $c++) {
                    $letter = Coordinate::stringFromColumnIndex($c);
                    $header = trim((string) $sheet->getCell($letter . $headerRow)->getValue());
                    if ($header !== '') {
                        $headerMap[$header] = $letter;
                    }
                }

                foreach ($selectedHeaders as $h) {
                    if (! isset($headerMap[$h])) {
                        abort(422, "Kolom '{$h}' tidak ditemukan di Excel");
                    }
                }

                $highestRow = $sheet->getHighestRow();

                for ($r = $dataStart; $r <= $highestRow; $r++) {

                    $item = [];

                    foreach ($selectedHeaders as $h) {
                        $cell = $sheet->getCell($headerMap[$h] . $r);

                        if (in_array($h, $this->forceStringColumns, true)) {
                            $item[$h] = trim((string) $cell->getFormattedValue());
                        } elseif (in_array($h, $this->dateColumns, true)) {
                            $item[$h] = $this->smartDateTimeValue(
                                $cell->getValue() ?: $cell->getFormattedValue()
                            );
                        } else {
                            $item[$h] = $this->smartValue($cell->getValue());
                        }
                    }

                    if (! array_filter($item)) {
                        continue;
                    }

                    $key = $this->makeKey(
                        $item['No. Pesanan'],
                        $item['Nomor Referensi SKU']
                    );

                    // UPDATE / INSERT MAP
                    $rowsMap[$key] = isset($rowsMap[$key])
                        ? array_merge($rowsMap[$key], $item)
                        : $item;
                }
            }

            /* ===============================
         * C) SNAPSHOT HASH
         * =============================== */
            ksort($rowsMap);
            $finalHash = hash('sha256', json_encode($rowsMap));

            $lastFile = InvoiceFilePesanan::where('seller_id', $request->seller_id)
                ->where('schema_id', $request->schema_id)
                ->latest('uploaded_at')
                ->first();

            if ($lastFile && $lastFile->data_hash === $finalHash) {
                DB::rollBack();

                return response()->json([
                    'status' => true,
                    'message' => 'Tidak ada perubahan data',
                    'redirect' => url('/admin-panel/pesanan/' . $lastFile->id . '/show'),
                ]);
            }

            /* ===============================
         * D) SIMPAN SNAPSHOT BARU
         * =============================== */
            $upload = InvoiceFilePesanan::create([
                'seller_id' => $request->seller_id,
                'schema_id' => $request->schema_id,
                'uploaded_at' => now(),
                'total_rows' => count($rowsMap),
                'data_hash' => $finalHash,
            ]);

            $rows = array_values($rowsMap);
            foreach (array_chunk($rows, $chunkSize) as $i => $chunk) {
                InvoiceDataPesanan::create([
                    'invoice_file_pesanan_id' => $upload->id,
                    'chunk_index' => $i,
                    'payload' => [
                        'headers' => $selectedHeaders,
                        'rows' => $chunk,
                    ],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'redirect' => url('/admin-panel/pesanan/' . $upload->id . '/show'),
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
        $limit = $perPage;

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
            'rows' => $rows,
            'currentPage' => $page,
            'perPage' => $perPage,
            'total' => $upload->total_rows,
            'upload' => $upload,
        ]);
    }

    public function show(string $id)
    {
        try {

            DB::beginTransaction();

            $file = InvoiceFilePesanan::with([
                'seller.platform',
                'schema',
                'chunks' => function ($q) {
                    $q->orderBy('chunk_index');
                },
            ])->findOrFail($id);

            $firstChunk = $file->chunks->first();

            $dbColumns = collect(Schema::getColumnListing('shopee_pesanan'))
                ->reject(fn($c) => in_array($c, ['id', 'uuid', 'created_at', 'updated_at']))
                ->values();

            DB::commit();

            return view('pages.pesanan.show', [
                'file' => $file,
                'headers' => $firstChunk?->payload['headers'] ?? [],
                'rows' => collect($firstChunk?->payload['rows'] ?? [])->take(20),
                'chunkCount' => $file->chunks->count(),
                'dbColumns' => $dbColumns,
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            dd($e->getMessage());
        }
    }

    public function processDatabase(Request $request, $id)
    {
        $request->validate([
            'mapping' => 'required|array',
        ]);

        $mapping = array_filter($request->mapping);

        DB::beginTransaction();

        try {

            $file = InvoiceFilePesanan::with('chunks')->findOrFail($id);

            foreach ($file->chunks as $chunk) {

                foreach ($chunk->payload['rows'] as $row) {

                    $data = [
                        'uuid' => Str::uuid(),
                    ];

                    foreach ($mapping as $dbColumn => $excelKey) {

                        if (! array_key_exists($excelKey, $row)) {
                            continue;
                        }

                        $rawValue = $row[$excelKey];

                        // ==========================
                        // STRING WAJIB (NO PESANAN, SKU, RESI)
                        // ==========================
                        if (in_array($dbColumn, $this->forceStringDatabaseColumns, true)) {
                            $data[$dbColumn] = trim((string) $rawValue);

                            continue;
                        }

                        // ==========================
                        // DATETIME
                        // ==========================
                        if (in_array($dbColumn, $this->dateDatabaseColumns, true)) {
                            $data[$dbColumn] = $this->smartDateTimeValue($rawValue);

                            continue;
                        }

                        // ==========================
                        // DEFAULT (ANGKA / STRING)
                        // ==========================
                        $data[$dbColumn] = $this->smartValue($rawValue);
                    }

                    // hanya uuid doang → skip
                    if (count($data) <= 1) {
                        continue;
                    }

                    ShopeePesanan::create($data);
                }
            }

            DB::commit();

            return back()->with('success', 'Data pesanan berhasil diproses');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
