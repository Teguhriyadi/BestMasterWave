<?php

namespace App\Http\Controllers\Transaction\Shopee;

use App\Http\Controllers\Controller;
use App\Models\ShopeePendapatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SelisihOngkirController extends Controller
{
    public function index()
    {
        try {

            $minusOrders = ShopeePendapatan::select(
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
                ->get()
                ->keyBy(fn($i) => $i->nama_seller . '|' . $i->no_pesanan);

            $data['shopee_pendapatan'] = ShopeePendapatan::query()
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
                    'shopee_pesanan.return_qty'
                )
                ->get()
                ->map(function ($item) use ($minusOrders) {

                    $key = $item->nama_seller . '|' . $item->no_pesanan;
                    $item->total_all = $minusOrders[$key]->total_all;

                    return $item;
                });

            return view(
                "pages.modules.transaction.shopee.selisih-ongkir.index",
                $data
            );
        } catch (\Exception $e) {
            return back()->with("error", $e->getMessage());
        }
    }
}
