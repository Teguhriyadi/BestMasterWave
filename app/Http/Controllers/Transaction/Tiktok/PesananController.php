<?php

namespace App\Http\Controllers\Transaction\Tiktok;

use App\Excel\HeaderOnlyFilter;
use App\Helpers\AuthDivisi;
use App\Http\Controllers\Controller;
use App\Http\Mapper\TiktokMapper;
use App\Http\Services\SellerService;
use App\Models\InvoiceDataTiktokPesanan;
use App\Models\InvoiceFileTiktokPesanan;
use App\Models\InvoiceSchemaTiktokPesanan;
use App\Models\Platform;
use App\Models\Seller;
use App\Models\TiktokPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class PesananController extends Controller
{
    public function __construct(
        protected SellerService $seller_service
    ) {}

    protected array $forceIntegerColumns = [
        'quantity',
        'sku_quantity_of_return',
        'sku_unit_original',
        'sku_subtotal_before_discount',
        'sku_platform_discount',
        'sku_seller_discount',
        'sku_subtotal_after_discount',
        'shipping_fee_after_discount',
        'original_shipping_fee',
        'shipping_fee_seller_discount',
        'shipping_fee_platform_discount',
        'payment_platform_discount',
        'buyer_service_fee',
        'handling_fee',
        'shipping_insurance',
        'item_insurance',
        'order_amount',
        'order_refund_amount'
    ];

    protected array $dateTimeColumns = [
        'created_time',
        'paid_time',
        'rts_time',
        'shipped_time',
        'delivered_time',
        'cancelled_time',
    ];

    public function index()
    {
        try {

            if (empty(Auth::user()->one_divisi_roles)) {
                return redirect()->to("/admin-panel/shopee-pesanan/data");
            }

            $platform = Platform::where("slug", "tiktok")->firstOrFail();
            $data["seller"] = Seller::where("status", "1")
                ->where("divisi_id", AuthDivisi::id())
                ->where("platform_id", $platform->id)
                ->get();

            return view('pages.modules.transaction.tiktok.pesanan.upload', $data);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls']);
        $path = $request->file('file')->getPathname();

        $sheetName = 'OrderSKUList';
        $headerRow = 1;

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);
        $reader->setLoadSheetsOnly([$sheetName]);
        $reader->setReadFilter(new HeaderOnlyFilter());

        $spreadsheet = $reader->load($path);
        $sheet = $spreadsheet->getSheetByName($sheetName);

        if (!$sheet) {
            return response()->json([
                'status'  => false,
                'message' => "Sheet '{$sheetName}' tidak ditemukan"
            ], 422);
        }

        $headers = [];
        $highestColumn = $sheet->getHighestColumn();
        $maxCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        for ($i = 1; $i <= $maxCol; $i++) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
            $val = trim((string) $sheet->getCell($col . $headerRow)->getValue());

            if ($val !== '') {
                $headers[$col] = $val;
            }
        }

        if (empty($headers)) {
            return response()->json([
                'status'  => false,
                'message' => 'Header tidak ditemukan di baris 1'
            ], 422);
        }

        $headerHash = hash('sha256', json_encode(array_values($headers)));

        $existingSchema = InvoiceSchemaTiktokPesanan::where([
            'header_hash' => $headerHash,
            'divisi_id'   => AuthDivisi::id(),
        ])->first();

        return response()->json([
            'status'      => true,
            'headers'     => $headers,
            'header_hash' => $headerHash,
            'schema_id'   => $existingSchema?->id
        ]);
    }

    public function normalizeValue($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

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

    public function smartDateTimeValue($value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            $value = trim($value);
            if ($value === '') return null;

            if (preg_match('/^\d{4}-\d{2}-\d{2}/', $value)) {
                return date('Y-m-d H:i:s', strtotime($value));
            }

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
            'file'        => 'required|mimes:xlsx,xls',
            'columns'     => 'required|array',
            'seller_id'   => 'required',
            'header_hash' => 'required'
        ]);

        $divisiId = AuthDivisi::id();

        try {

            $path = $request->file('file')->getPathname();
            $reader = IOFactory::createReaderForFile($path);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($path);

            $sheetName = 'OrderSKUList';
            $headerRow = 1;
            $dataStart = 3;

            $sheet = $spreadsheet->getSheetByName($sheetName);

            if (!$sheet) {
                return response()->json([
                    'status'  => false,
                    'message' => "Sheet '{$sheetName}' tidak ditemukan"
                ], 422);
            }

            $noPesananColLetter = null;

            foreach ($request->columns as $colLetter => $label) {
                if (trim($label) === 'Order ID') {
                    $noPesananColLetter = $colLetter;
                    break;
                }
            }

            if ($noPesananColLetter === null) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Kolom "Order ID" tidak ditemukan'
                ], 422);
            }

            $existingSchema = InvoiceSchemaTiktokPesanan::where([
                'header_hash' => $request->header_hash,
                'divisi_id'   => $divisiId,
            ])->first();

            DB::beginTransaction();

            $file = InvoiceFileTiktokPesanan::create([
                'id'           => (string) Str::uuid(),
                'seller_id'    => $request->seller_id,
                'schema_id'    => $existingSchema?->id,
                'header_hash'  => $request->header_hash,
                'uploaded_at'  => now(),
                'processed_at' => null,
                'total_rows'   => 0,
                'divisi_id'    => $divisiId,
            ]);

            $highestRow = $sheet->getHighestRow();
            $buffer = [];
            $chunk  = 0;
            $total  = 0;

            for ($row = $dataStart; $row <= $highestRow; $row++) {

                $noPesanan = strtoupper(trim(
                    (string) $sheet->getCell($noPesananColLetter . $row)->getFormattedValue()
                ));

                if ($noPesanan === '') {
                    continue;
                }

                $item = [];

                foreach ($request->columns as $colLetter => $headerLabel) {
                    $item[$headerLabel] = (string)
                    $sheet->getCell($colLetter . $row)->getFormattedValue();
                }

                $buffer[] = $item;
                $total++;

                if (count($buffer) === 500) {
                    InvoiceDataTiktokPesanan::create([
                        'invoice_file_tiktok_pesanan_id' => $file->id,
                        'chunk_index' => $chunk++,
                        'payload'     => ['rows' => $buffer],
                        'divisi_id'   => $divisiId,
                    ]);
                    $buffer = [];
                }
            }

            if (!empty($buffer)) {
                InvoiceDataTiktokPesanan::create([
                    'invoice_file_tiktok_pesanan_id' => $file->id,
                    'chunk_index' => $chunk,
                    'payload'     => ['rows' => $buffer],
                    'divisi_id'   => $divisiId,
                ]);
            }

            if ($total === 0) {
                DB::rollBack();
                return response()->json([
                    'status'  => false,
                    'message' => 'Tidak ada data yang dapat diproses'
                ], 422);
            }

            $file->update(['total_rows' => $total]);
            DB::commit();

            return response()->json([
                'status'   => true,
                'message'  => "Berhasil memuat {$total} baris data pesanan",
                'redirect' => url("/admin-panel/tiktok-pesanan/{$file->id}/show"),
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id)
    {
        $file = InvoiceFileTiktokPesanan::with(['seller.platform', 'schema', 'chunks'])->findOrFail($id);
        $firstChunk = $file->chunks->first();

        $needMapping = is_null($file->processed_at) &&
            (is_null($file->schema_id) || empty($file->schema?->columns_mapping));

        $dbColumns = collect(DB::getSchemaBuilder()->getColumnListing('tiktok_pesanan'))
            ->reject(fn($c) => in_array($c, [
                'id',
                'uuid',
                'created_at',
                'updated_at',
                'created_by',
                'divisi_id',
                'nama_seller'
            ]))
            ->values();

        return view('pages.modules.transaction.tiktok.pesanan.show', [
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
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $file = InvoiceFileTiktokPesanan::with(['schema', 'seller'])
            ->findOrFail($fileId);

        $divisiId = AuthDivisi::id();

        $mapping = $request->input('mapping') ?? $file->schema?->columns_mapping;

        if (!$mapping || empty($mapping['order_id'])) {
            return back()->with(
                'error',
                'Mapping tidak valid. Kolom Order ID wajib di-map.'
            );
        }

        $excelOrderIdHeader = $mapping['order_id'];

        $schema = InvoiceSchemaTiktokPesanan::updateOrCreate(
            [
                'header_hash' => $file->header_hash,
                'divisi_id'   => $divisiId,
            ],
            [
                'columns_mapping' => $mapping,
            ]
        );

        $file->update(['schema_id' => $schema->id]);

        $now      = now();
        $userId   = Auth::id();
        $sellerNm = $file->seller->nama;

        DB::beginTransaction();

        try {

            InvoiceDataTiktokPesanan::where([
                'invoice_file_tiktok_pesanan_id' => $fileId,
                'divisi_id' => $divisiId,
            ])->chunkById(300, function ($chunks) use (
                $mapping,
                $excelOrderIdHeader,
                $divisiId,
                $now,
                $userId,
                $sellerNm
            ) {

                foreach ($chunks as $chunk) {

                    $rows = $chunk->payload['rows'] ?? [];
                    $batch = [];

                    foreach ($rows as $row) {

                        $orderId = trim((string) ($row[$excelOrderIdHeader] ?? ''));

                        if ($orderId === '') {
                            continue;
                        }

                        $data = [
                            'uuid'        => Str::uuid(),
                            'order_id'    => $orderId,
                            'nama_seller' => $sellerNm,
                            'divisi_id'   => $divisiId,
                            'created_by'  => $userId,
                            'updated_at'  => $now,
                        ];

                        foreach ($mapping as $dbColumn => $excelHeader) {

                            if (
                                $dbColumn === 'order_id' ||
                                empty($excelHeader) ||
                                !isset($row[$excelHeader])
                            ) {
                                continue;
                            }

                            $value = $row[$excelHeader];

                            if (in_array($dbColumn, $this->dateTimeColumns, true)) {
                                $data[$dbColumn] = $this->parseExcelDate($value);
                                continue;
                            }

                            if (in_array($dbColumn, $this->forceIntegerColumns, true)) {
                                $data[$dbColumn] = is_numeric($value)
                                    ? (int) round($value)
                                    : 0;
                                continue;
                            }

                            $data[$dbColumn] = $this->smartValue($value);
                        }

                        $batch[] = $data;
                    }

                    if ($batch) {
                        TiktokPesanan::upsert(
                            $batch,
                            ['order_id', 'divisi_id'],
                            array_keys($batch[0])
                        );
                    }
                }
            });

            $file->update(['processed_at' => now()]);
            DB::commit();

            return redirect()
                ->to('/admin-panel/tiktok-pesanan')
                ->with('success', 'Data pesanan berhasil diproses berdasarkan Order ID.');
        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->with(
                'error',
                'Gagal memproses database: ' . $e->getMessage()
            );
        }
    }

    public function kelola(Request $request)
    {
        $data['seller'] = $this->seller_service->list_seller();

        if ($request->ajax()) {
            $query = TiktokPesanan::query();

            if ($request->nama_seller) {
                $query->where('nama_seller', $request->nama_seller);
            }

            $filterBy = $request->filter_by;
            $dari     = $request->dari;
            $sampai   = $request->sampai;

            $allowedColumns = [
                'waktu_pesanan_dibuat',
                'waktu_pembayaran_dilakukan',
            ];

            if (in_array($filterBy, $allowedColumns) && $dari && $sampai) {
                $query->whereBetween($filterBy, [
                    $dari . ' 00:00:00',
                    $sampai . ' 23:59:59'
                ]);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('waktu_pesanan_dibuat', function ($row) {
                    return $row->waktu_pesanan_dibuat ? \Carbon\Carbon::parse($row->waktu_pesanan_dibuat)->translatedFormat('d F Y H:i:s') : '-';
                })
                ->editColumn('waktu_pembayaran_dilakukan', function ($row) {
                    return $row->waktu_pembayaran_dilakukan ? \Carbon\Carbon::parse($row->waktu_pembayaran_dilakukan)->translatedFormat('d F Y H:i:s') : '-';
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . url('/admin-panel/tiktok-pesanan/data/' . $row->uuid . '/detail') . '" class="btn btn-info btn-sm">
                            <i class="fa fa-search"></i> Detail
                        </a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view("pages.modules.transaction.tiktok.pesanan.kelola", $data);
    }

    public function detail($uuid)
    {
        try {

            DB::beginTransaction();

            $data["detail"] = TiktokPesanan::where("uuid", $uuid)->first();

            if (empty($data["detail"])) return redirect()->to("/admin-panel/tiktok-pesanan/data")->with("error", "Data Tidak Ditemukan");

            $data["details"] = TiktokMapper::mapPesanan($data["detail"]);

            DB::commit();

            return view("pages.modules.transaction.tiktok.pesanan.detail", $data);
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->to("/admin-panel/shopee-pesanan/data")->with("error", $e->getMessage());
        }
    }

    protected function parseExcelDate($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        if (is_string($value)) {
            try {
                return \Carbon\Carbon::createFromFormat(
                    'd/m/Y H:i:s',
                    trim($value)
                )->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }
}
