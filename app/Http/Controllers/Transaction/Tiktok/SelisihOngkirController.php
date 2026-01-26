<?php

namespace App\Http\Controllers\Transaction\Tiktok;

use App\Http\Controllers\Controller;
use App\Models\TiktokPendapatan;
use Illuminate\Support\Facades\DB;

class SelisihOngkirController extends Controller
{
    public function index()
    {
        try {

            $minusOrders = TiktokPendapatan::select(
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
                ->get()
                ->keyBy(fn($i) => $i->nama_seller . '|' . $i->order_or_adjustment_id);

            $data['tiktok_pendapatan'] = TiktokPendapatan::query()
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
                ->map(function ($item) use ($minusOrders) {

                    $key = $item->nama_seller . '|' . $item->order_or_adjustment_id;
                    $item->total_all = $minusOrders[$key]->total_all;

                    return $item;
                });

            return view(
                "pages.modules.transaction.tiktok.selisih-ongkir.index",
                $data
            );
        } catch (\Exception $e) {
            return back()->with("error", $e->getMessage());
        }
    }
}
