<?php

namespace App\Http\Controllers\Transaction\Tiktok;

use App\Http\Controllers\Controller;
use App\Http\Services\SellerService;
use App\Models\TiktokPendapatan;
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

            $data["seller"] = $this->seller_service->list_seller_tiktok_divisi();

            if ($request->ajax()) {

                $dari = $request->dari
                    ? Carbon::parse($request->dari)->startOfDay()
                    : Carbon::today()->startOfDay();

                $sampai = $request->sampai
                    ? Carbon::parse($request->sampai)->endOfDay()
                    : Carbon::now();

                $minusOrdersQuery = TiktokPendapatan::select(
                    'nama_seller',
                    'order_or_adjustment_id',
                    DB::raw('
                        SUM(
                            shipping_costs_passed +
                            replacement_shipping_fee +
                            exchange_shipping_fee +
                            shipping_cost_borne +
                            shipping_cost_paid +
                            refunded_shipping_cost_paid +
                            return_shipping_costs +
                            shipping_cost_subsidy
                        ) AS total_all
                    ')
                )
                    ->groupBy('nama_seller', 'order_or_adjustment_id')
                    ->having('total_all', '<', 0)
                    ->whereBetween('order_settled_time', [$dari, $sampai]);

                if ($request->nama_seller) {
                    $minusOrdersQuery->where('nama_seller', $request->nama_seller);
                }

                $minusOrders = $minusOrdersQuery->get()
                    ->keyBy(fn($i) => $i->nama_seller . '|' . $i->order_or_adjustment_id);

                $query = TiktokPendapatan::query()
                    ->leftJoin(
                        'tiktok_pesanan',
                        'tiktok_pendapatan.order_or_adjustment_id',
                        '=',
                        'tiktok_pesanan.order_id'
                    )
                    ->whereIn(
                        DB::raw("CONCAT(tiktok_pendapatan.nama_seller, '|', tiktok_pendapatan.order_or_adjustment_id)"),
                        $minusOrders->keys()
                    )
                    ->select(
                        'tiktok_pendapatan.*',
                        'tiktok_pesanan.sku_id',
                        'tiktok_pesanan.quantity'
                    );

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('total_all', function ($row) use ($minusOrders) {
                        $key = $row->nama_seller . '|' . $row->order_or_adjustment_id;
                        $value = $minusOrders[$key]->total_all ?? 0;
                        return number_format($value, 0, ',', '.');
                    })
                    ->editColumn('shipping_costs_passed', function ($row) {
                        return number_format($row->shipping_costs_passed ?? 0, 0, ',', '.');
                    })
                    ->editColumn('replacement_shipping_fee', function ($row) {
                        return number_format($row->replacement_shipping_fee ?? 0, 0, ',', '.');
                    })
                    ->editColumn('exchange_shipping_fee', function ($row) {
                        return number_format($row->exchange_shipping_fee ?? 0, 0, ',', '.');
                    })
                    ->editColumn('shipping_cost_borne', function ($row) {
                        return number_format($row->shipping_cost_borne ?? 0, 0, ',', '.');
                    })
                    ->editColumn('shipping_cost_paid', function ($row) {
                        return number_format($row->shipping_cost_paid ?? 0, 0, ',', '.');
                    })
                    ->editColumn('refunded_shipping_cost_paid', function ($row) {
                        return number_format($row->refunded_shipping_cost_paid ?? 0, 0, ',', '.');
                    })
                    ->editColumn('return_shipping_costs', function ($row) {
                        return number_format($row->return_shipping_costs ?? 0, 0, ',', '.');
                    })
                    ->editColumn('shipping_cost_subsidy', function ($row) {
                        return number_format($row->shipping_cost_subsidy ?? 0, 0, ',', '.');
                    })

                    ->make(true);
            }

            return view(
                "pages.modules.transaction.tiktok.selisih-ongkir.index",
                $data
            );
        } catch (\Exception $e) {
            return back()->with("error", $e->getMessage());
        }
    }

    public function download(Request $request)
    {
        $minusOrdersQuery = TiktokPendapatan::select(
            'nama_seller',
            'order_or_adjustment_id',
            DB::raw('
            SUM(
                shipping_costs_passed +
                replacement_shipping_fee +
                exchange_shipping_fee +
                shipping_cost_borne +
                shipping_cost_paid +
                refunded_shipping_cost_paid +
                return_shipping_costs +
                shipping_cost_subsidy
            ) AS total_all
        ')
        )
            ->groupBy('nama_seller', 'order_or_adjustment_id')
            ->having('total_all', '<', 0);

        if ($request->nama_seller) {
            $minusOrdersQuery->where('nama_seller', $request->nama_seller);
        }

        if ($request->dari && $request->sampai) {
            $minusOrdersQuery->whereBetween('order_settled_time', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59',
            ]);
        }

        $minusOrders = $minusOrdersQuery->get()
            ->keyBy(fn($i) => $i->nama_seller . '|' . $i->order_or_adjustment_id);

        $rows = TiktokPendapatan::query()
            ->leftJoin(
                'tiktok_pesanan',
                'tiktok_pendapatan.order_or_adjustment_id',
                '=',
                'tiktok_pesanan.order_id'
            )
            ->whereIn(
                DB::raw("CONCAT(tiktok_pendapatan.nama_seller, '|', tiktok_pendapatan.order_or_adjustment_id)"),
                $minusOrders->keys()
            )
            ->select(
                'tiktok_pendapatan.*',
                'tiktok_pesanan.sku_id',
                'tiktok_pesanan.quantity'
            )
            ->get()
            ->map(function ($row) use ($minusOrders) {
                $key = $row->nama_seller . '|' . $row->order_or_adjustment_id;
                $row->total_all = $minusOrders[$key]->total_all ?? 0;
                return $row;
            });

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'Nama Toko',
            'No. Pesanan',
            // 'Nama Kurir',
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
                (string) $row->order_or_adjustment_id,
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

        $filename = "tiktok_{$dari}-{$sampai}.xlsx";

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
