<?php

namespace App\Http\Controllers\Transaction\Shopee;

use App\Helpers\AuthDivisi;
use App\Http\Controllers\Controller;
use App\Http\Services\SellerService;
use App\Models\InvoiceDataPesanan;
use App\Models\InvoiceFilePesanan;
use App\Models\InvoiceSchemaPesanan;
use App\Models\Platform;
use App\Models\Seller;
use App\Models\ShopeePesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class PesananController extends Controller
{
    public function __construct(
        protected SellerService $seller_service
    ) {}

    protected array $dateColumns = [
        'Pesanan Harus Dikirimkan Sebelum (Menghindari keterlambatan)',
        'Waktu Pengiriman Diatur',
        'Waktu Pesanan Dibuat',
        'Waktu Pembayaran Dilakukan',
        'Waktu Pesanan Selesai'
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

            if (empty(Auth::user()->one_divisi_roles)) {
                return redirect()->to("/admin-panel/shopee/pesanan/data");
            }

            $platform = Platform::where("slug", "shopee")->firstOrFail();
            $data["seller"] = Seller::where("status", "1")
                ->where("divisi_id", AuthDivisi::id())
                ->where("platform_id", $platform->id)
                ->get();

            return view('pages.modules.transaction.shopee.pesanan.upload', $data);

        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }


    public function store(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls']);
        $path = $request->file('file')->getPathname();

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($path);
        $spreadsheet = $reader->load($path);

        $headers = [];
        $sheetName = null;
        $headerRow = 1;

        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            if (stripos($sheet->getTitle(), 'orders') === false && stripos($sheet->getTitle(), 'order') === false) {
                continue;
            }

            $sheetName = $sheet->getTitle();
            $highestColumn = $sheet->getHighestColumn();
            $maxCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

            for ($i = 1; $i <= $maxCol; $i++) {
                $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
                $val = trim((string) $sheet->getCell($col . $headerRow)->getFormattedValue());
                if ($val !== '') {
                    $headers[$col] = $val;
                }
            }
            break;
        }

        if (!$sheetName || empty($headers)) {
            return response()->json(['status' => false, 'message' => 'Format file tidak dikenali (Header baris 1 tidak ditemukan pada sheet orders)'], 422);
        }

        $normalized = array_values($headers);
        $headerHash = hash('sha256', json_encode($normalized));

        $existingSchema = InvoiceSchemaPesanan::where('header_hash', $headerHash)
            ->where("divisi_id", AuthDivisi::id())
            ->first();

        return response()->json([
            'status'       => true,
            'headers'      => $headers,
            'header_hash'  => $headerHash,
            'schema_id'    => $existingSchema ? $existingSchema->id : null,
            'required_columns' => [
                'No. Pesanan',
                'Nomor Referensi SKU',
                'Status Pesanan'
            ],
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

        try {
            $path = $request->file('file')->getPathname();
            $spreadsheet = IOFactory::createReaderForFile($path)->load($path);

            $noPesananColLetter = array_search('No. Pesanan', $request->columns);
            $skuColLetter       = array_search('Nomor Referensi SKU', $request->columns);

            if (!$noPesananColLetter || !$skuColLetter) {
                return response()->json(['status' => false, 'message' => 'Kolom "No. Pesanan" atau "Nomor Referensi SKU" tidak ditemukan'], 422);
            }

            $existingSchema = InvoiceSchemaPesanan::where('header_hash', $request->header_hash)
                ->where("divisi_id", AuthDivisi::id())
                ->first();

            DB::beginTransaction();

            $file = InvoiceFilePesanan::create([
                'id'           => (string) Str::uuid(),
                'seller_id'    => $request->seller_id,
                'schema_id'    => $existingSchema ? $existingSchema->id : null,
                'header_hash'  => $request->header_hash,
                'uploaded_at'  => now(),
                'total_rows'   => 0,
                'processed_at' => null,
                'divisi_id'    => AuthDivisi::id()
            ]);

            $buffer = [];
            $chunk = 0;
            $total = 0;

            foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
                if (stripos($sheet->getTitle(), 'orders') === false && stripos($sheet->getTitle(), 'order') === false) continue;

                $highestRow = $sheet->getHighestRow();

                for ($row = 2; $row <= $highestRow; $row++) {
                    $noPesanan = strtoupper(trim((string)$sheet->getCell($noPesananColLetter . $row)->getFormattedValue()));
                    $sku       = trim((string)$sheet->getCell($skuColLetter . $row)->getFormattedValue());

                    if ($noPesanan === '') continue;

                    $item = [];
                    foreach ($request->columns as $colLetter => $headerLabel) {
                        $item[$headerLabel] = (string)$sheet->getCell($colLetter . $row)->getFormattedValue();
                    }

                    $buffer[] = $item;
                    $total++;

                    if (count($buffer) === 500) {
                        InvoiceDataPesanan::create([
                            'invoice_file_pesanan_id' => $file->id,
                            'chunk_index' => $chunk++,
                            'payload' => ['rows' => $buffer],
                            'divisi_id' => AuthDivisi::id()
                        ]);
                        $buffer = [];
                    }
                }
                break;
            }

            if (!empty($buffer)) {
                InvoiceDataPesanan::create([
                    'invoice_file_pesanan_id' => $file->id,
                    'chunk_index' => $chunk,
                    'payload' => ['rows' => $buffer],
                    'divisi_id' => AuthDivisi::id()
                ]);
            }

            if ($total === 0) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'Tidak ada data yang dapat diproses.'], 422);
            }

            $file->update(['total_rows' => $total]);
            DB::commit();

            return response()->json([
                'status'   => true,
                'message'  => "Berhasil memuat $total baris data pesanan.",
                'redirect' => url("/admin-panel/shopee/pesanan/{$file->id}/show")
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        $file = InvoiceFilePesanan::with(['seller.platform', 'schema', 'chunks'])->findOrFail($id);
        $firstChunk = $file->chunks->first();

        $needMapping = is_null($file->processed_at) &&
            (is_null($file->schema_id) || empty($file->schema?->columns_mapping));

        $dbColumns = collect(DB::getSchemaBuilder()->getColumnListing('shopee_pesanan'))
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

        return view('pages.modules.transaction.shopee.pesanan.show', [
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
        $file = InvoiceFilePesanan::findOrFail($fileId);

        $mapping = $request->input('mapping');

        if (!$mapping && $file->schema_id) {
            $mapping = $file->schema->columns_mapping;
        }

        if (!$mapping) {
            return back()->with('error', 'Mapping kolom tidak tersedia.');
        }

        $nama_seller = $request->nama_seller;
        DB::beginTransaction();
        try {
            $schema = InvoiceSchemaPesanan::updateOrCreate(
                ['header_hash' => $file->header_hash],
                [
                    'columns_mapping' => $mapping,
                    'divisi_id' => AuthDivisi::id()
                ]
            );

            $file->update(['schema_id' => $schema->id]);

            $chunks = InvoiceDataPesanan::where('invoice_file_pesanan_id', $fileId)
                ->orderBy('chunk_index', 'asc')
                ->get();

            foreach ($chunks as $chunk) {
                $rows = $chunk->payload['rows'] ?? [];
                foreach ($rows as $row) {

                    $headerNoPesanan = $mapping['no_pesanan'] ?? 'No. Pesanan';
                    $headerSKU       = $mapping['nomor_referensi_sku'] ?? 'Nomor Referensi SKU';

                    $noPesanan = trim((string)($row[$headerNoPesanan] ?? ''));
                    $skuValue  = trim((string)($row[$headerSKU] ?? ''));

                    if (empty($noPesanan)) continue;

                    $saveData = [
                        'seller_id'       => $file->seller_id,
                        'invoice_file_id' => $file->id,
                        'nama_seller'     => $nama_seller
                    ];

                    foreach ($mapping as $dbColumn => $excelHeader) {
                        if (empty($excelHeader)) continue;

                        $value = $row[$excelHeader] ?? null;

                        if (in_array($dbColumn, $this->dateDatabaseColumns)) {
                            $saveData[$dbColumn] = $this->smartDateTimeValue($value);
                        } elseif (in_array($dbColumn, $this->forceStringDatabaseColumns)) {
                            $saveData[$dbColumn] = trim((string)$value);
                        } else {
                            $saveData[$dbColumn] = $this->smartValue($value);
                        }
                    }

                    ShopeePesanan::updateOrCreate(
                        [
                            'no_pesanan'          => $noPesanan,
                            'nomor_referensi_sku' => $skuValue
                        ],
                        $saveData
                    );
                }
            }

            $file->update(['processed_at' => now()]);
            DB::commit();

            return redirect()
                ->to("/admin-panel/shopee/pesanan")
                ->with('success', 'Data pesanan berhasil di-import/update.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses database: ' . $e->getMessage());
        }
    }

    public function kelola(Request $request)
    {
        $data['seller'] = $this->seller_service->list_seller();

        if ($request->ajax()) {
            $query = ShopeePesanan::query();

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
                    return '<a href="' . url('/admin-panel/shopee/pesanan/data/' . $row->uuid . '/detail') . '" class="btn btn-info btn-sm">
                            <i class="fa fa-search"></i> Detail
                        </a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view("pages.modules.transaction.shopee.pesanan.kelola", $data);
    }

    public function detail($uuid)
    {
        try {

            DB::beginTransaction();

            $data["detail"] = ShopeePesanan::where("uuid", $uuid)->first();

            if (empty($data["detail"])) return redirect()->to("/admin-panel/shopee/pesanan/data")->with("error", "Data Tidak Ditemukan");

            DB::commit();

            return view("pages.modules.transaction.shopee.pesanan.detail", $data);
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->to("/admin-panel/shopee/pesanan/data")->with("error", $e->getMessage());
        }
    }
}
