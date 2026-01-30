<?php

namespace App\Http\Controllers\Transaction\Tiktok;

use App\Http\Controllers\Controller;
use App\Http\Services\SellerService;
use App\Models\TiktokPendapatan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LaporanController extends Controller
{
    public function __construct(
        protected SellerService $seller_service
    ) {}

    public function index(Request $request)
    {
        $data['seller'] = $this->seller_service->list_seller_all();

        if ($request->ajax()) {
            $filterBy = $request->filter_by ?? 'order_settled_time';

            $dari = $request->dari
                ? Carbon::parse($request->dari)->startOfDay()
                : Carbon::now()->subDays(30)->startOfDay();

            $sampai = $request->sampai
                ? Carbon::parse($request->sampai)->endOfDay()
                : Carbon::now();

            $query = TiktokPendapatan::with([
                'pesanan:id,order_id,sku_id,quantity,harga_modal'
            ]);

            if ($request->nama_seller) {
                $query->where('nama_seller', $request->nama_seller);
            }

            $allowedColumns = ['order_created_time', 'order_settled_time'];

            if (in_array($filterBy, $allowedColumns)) {
                $query->whereBetween($filterBy, [$dari, $sampai]);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('sku_id', function ($row) {
                    return $row->pesanan
                        ->pluck('sku_id')
                        ->map(fn ($sku_id) => "<span class='badge badge-info mr-1'>{$sku_id}</span>")
                        ->implode(' ');
                })
                ->addColumn('quantity', function ($row) {
                    return $row->pesanan
                        ->pluck('quantity')
                        ->map(fn ($quantity) => "<span class='badge badge-info mr-1'>{$quantity}</span>")
                        ->implode(' ');
                })
                ->addColumn('harga_modal', function ($row) {
                    return $row->pesanan
                        ->pluck('harga_modal')
                        ->map(fn ($harga_modal) => "<span class='badge badge-info mr-1'>{$harga_modal}</span>")
                        ->implode(' ');
                })
                ->editColumn('waktu_pesanan', function ($row) {
                    return $row->waktu_pesanan ? Carbon::parse($row->waktu_pesanan)->translatedFormat('d F Y') : '-';
                })
                ->editColumn('tanggal_dana_dilepaskan', function ($row) {
                    return $row->tanggal_dana_dilepaskan ? Carbon::parse($row->tanggal_dana_dilepaskan)->translatedFormat('d F Y') : '-';
                })
                ->editColumn('order_created_time', function ($row) {
                    return $row->order_created_time ? Carbon::parse($row->order_created_time)->translatedFormat('d F Y') : '-';
                })
                ->editColumn('order_settled_time', function ($row) {
                    return $row->order_settled_time ? Carbon::parse($row->order_settled_time)->translatedFormat('d F Y') : '-';
                })
                ->editColumn('total_settlement_amount', function ($row) {
                    return 'Rp ' . number_format($row->total_settlement_amount, 0, ',', '.');
                })
                ->editColumn('total_revenue', function ($row) {
                    return 'Rp ' . number_format($row->total_revenue, 0, ',', '.');
                })
                ->editColumn('total', function ($row) {
                    $total = $row->tiktok_shop_commission_fee + $row->flat_fee + $row->sales_fee + $row->pre_order_service_fee + $row->mall_service_fee + $row->payment_fee;
                    return 'Rp ' . number_format($total, 0, ',', '.');
                })
                ->editColumn('ongkir', function ($row) {
                    $ongkir = $row->shipping_cost + $row->shipping_costs_passed + $row->replacement_shipping_fee + $row->exchange_shipping_fee + $row->shipping_cost_borne + $row->shipping_cost_paid;
                    return 'Rp ' . number_format($ongkir, 0, ',', '.');
                })
                ->editColumn('ongkir_refund', function ($row) {
                    $ongkir_refund = $row->refunded_shipping_cost_paid + $row->return_shipping_costs + $row->shipping_cost_subsidy;
                    return 'Rp ' . number_format($ongkir_refund, 0, ',', '.');
                })
                ->editColumn('affiliate', function ($row) {
                    $affiliate = $row->affiliate_commission + $row->affiliate_partner_commission + $row->affiliate_shop_ads_commission;
                    return 'Rp ' . number_format($affiliate, 0, ',', '.');
                })
                ->editColumn('laba_rugi', function ($row) {
                    $totalModal = $row->pesanan->sum('harga_modal');
                    $harga_modal = $row->total_settlement_amount - $totalModal;
                    return 'Rp ' . number_format($harga_modal, 0, ',', '.');
                })
                ->rawColumns(['sku_id', 'quantity', 'harga_modal'])
                ->make(true);
        }

        return view('pages.modules.transaction.tiktok.laporan.index', $data);
    }
}
