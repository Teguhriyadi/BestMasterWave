<?php

namespace App\Http\Controllers\Transaction\Shopee;

use App\Http\Controllers\Controller;
use App\Http\Services\SellerService;
use App\Models\ShopeePendapatan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\Facades\DataTables;

class SelisihOngkirController extends Controller
{
    public function __construct(
        protected SellerService $seller_service
    ) {}

    public function index(Request $request)
    {
        try {

            $data["seller"] = $this->seller_service->list_seller_shopee_divisi();

            if ($request->ajax()) {
                $dari = $request->dari
                    ? Carbon::parse($request->dari)->startOfDay()
                    : Carbon::today()->startOfDay();

                $sampai = $request->sampai
                    ? Carbon::parse($request->sampai)->endOfDay()
                    : Carbon::now();

                $minusOrdersQuery = ShopeePendapatan::select(
                    'nama_seller',
                    'no_pesanan',
                    DB::raw('
                        SUM(
                            ongkir_dibayar +
                            diskon_ongkir_ditanggung +
                            gratis_ongkir_shopee +
                            ongkir_diteruskan_shopee +
                            ongkos_kirim_pengembalian +
                            kembali_ke_biaya_pengiriman +
                            pengembalian_biaya_kirim
                        ) AS total_all
                    ')
                )
                    ->groupBy('nama_seller', 'no_pesanan')
                    ->having('total_all', '<', 0)
                    ->whereBetween('tanggal_dana_dilepaskan', [$dari, $sampai]);

                if ($request->nama_seller) {
                    $minusOrdersQuery->where('nama_seller', $request->nama_seller);
                }

                $minusOrders = $minusOrdersQuery->get()
                    ->keyBy(fn($i) => $i->nama_seller . '|' . $i->no_pesanan);

                $query = ShopeePendapatan::query()
                    ->leftJoin(
                        'shopee_pesanan',
                        'shopee_pendapatan.no_pesanan',
                        '=',
                        'shopee_pesanan.no_pesanan'
                    )
                    ->whereIn(
                        DB::raw("CONCAT(shopee_pendapatan.nama_seller, '|', shopee_pendapatan.no_pesanan)"),
                        $minusOrders->keys()
                    )
                    ->select(
                        'shopee_pendapatan.*',
                        'shopee_pesanan.nomor_referensi_sku',
                        'shopee_pesanan.sku_induk'
                    );

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('total_all', function ($row) use ($minusOrders) {
                        $key = $row->nama_seller . '|' . $row->no_pesanan;
                        $value = $minusOrders[$key]->total_all ?? 0;
                        return number_format($value, 0, ',', '.');
                    })
                    ->editColumn('ongkir_dibayar', function ($row) {
                        return number_format($row->ongkir_dibayar ?? 0, 0, ',', '.');
                    })
                    ->editColumn('diskon_ongkir_ditanggung', function ($row) {
                        return number_format($row->diskon_ongkir_ditanggung ?? 0, 0, ',', '.');
                    })
                    ->editColumn('gratis_ongkir_shopee', function ($row) {
                        return number_format($row->gratis_ongkir_shopee ?? 0, 0, ',', '.');
                    })
                    ->editColumn('ongkir_diteruskan_shopee', function ($row) {
                        return number_format($row->ongkir_diteruskan_shopee ?? 0, 0, ',', '.');
                    })
                    ->editColumn('ongkos_kirim_pengembalian', function ($row) {
                        return number_format($row->ongkos_kirim_pengembalian ?? 0, 0, ',', '.');
                    })
                    ->editColumn('kembali_ke_biaya_pengiriman', function ($row) {
                        return number_format($row->kembali_ke_biaya_pengiriman ?? 0, 0, ',', '.');
                    })
                    ->editColumn('pengembalian_biaya_kirim', function ($row) {
                        return number_format($row->pengembalian_biaya_kirim ?? 0, 0, ',', '.');
                    })

                    ->make(true);
            }

            return view(
                "pages.modules.transaction.shopee.selisih-ongkir.index",
                $data
            );
        } catch (\Exception $e) {
            return back()->with("error", $e->getMessage());
        }
    }

    public function download(Request $request)
    {
        $minusOrdersQuery = ShopeePendapatan::select(
            'nama_seller',
            'no_pesanan',
            DB::raw('
            SUM(
                ongkir_dibayar +
                diskon_ongkir_ditanggung +
                gratis_ongkir_shopee +
                ongkir_diteruskan_shopee +
                ongkos_kirim_pengembalian +
                kembali_ke_biaya_pengiriman +
                pengembalian_biaya_kirim
            ) AS total_all
        ')
        )
            ->groupBy('nama_seller', 'no_pesanan')
            ->having('total_all', '<', 0);

        if ($request->nama_seller) {
            $minusOrdersQuery->where('nama_seller', $request->nama_seller);
        }

        if ($request->dari && $request->sampai) {
            $minusOrdersQuery->whereBetween('tanggal_dana_dilepaskan', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59',
            ]);
        }

        $minusOrders = $minusOrdersQuery->get()
            ->keyBy(fn($i) => $i->nama_seller . '|' . $i->no_pesanan);

        $rows = ShopeePendapatan::query()
            ->leftJoin(
                'shopee_pesanan',
                'shopee_pendapatan.no_pesanan',
                '=',
                'shopee_pesanan.no_pesanan'
            )
            ->whereIn(
                DB::raw("CONCAT(shopee_pendapatan.nama_seller, '|', shopee_pendapatan.no_pesanan)"),
                $minusOrders->keys()
            )
            ->select(
                'shopee_pendapatan.*',
                'shopee_pesanan.nomor_referensi_sku',
                'shopee_pesanan.sku_induk'
            )
            ->get()
            ->map(function ($row) use ($minusOrders) {
                $key = $row->nama_seller . '|' . $row->no_pesanan;
                $row->total_all = $minusOrders[$key]->total_all ?? 0;
                return $row;
            });

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'Nama Toko',
            'No. Pesanan',
            'Nama Kurir',
        ];

        $sheet->fromArray($headers, null, 'A1');

        $rowNum = 2;
        foreach ($rows as $row) {

            $sheet->setCellValueExplicit(
                'A' . $rowNum,
                $row->nama_seller,
                DataType::TYPE_STRING
            );

            $sheet->setCellValueExplicit(
                'B' . $rowNum,
                (string) $row->no_pesanan,
                DataType::TYPE_STRING
            );

            $sheet->setCellValueExplicit(
                'C' . $rowNum,
                (string) $row->nama_kurir,
                DataType::TYPE_STRING
            );

            $rowNum++;
        }

        $sheet->getStyle('E2:M' . $rowNum)
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        $dari   = $request->dari
            ? Carbon::parse($request->dari)->format('Ymd')
            : 'awal';

        $sampai = $request->sampai
            ? Carbon::parse($request->sampai)->format('Ymd')
            : 'akhir';

        $filename = "shopee_{$dari}-{$sampai}.xlsx";

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }
}
