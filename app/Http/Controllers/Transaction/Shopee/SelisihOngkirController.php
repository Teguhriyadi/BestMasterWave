<?php

namespace App\Http\Controllers\Transaction\Shopee;

use App\Http\Controllers\Controller;
use App\Http\Services\SellerService;
use App\Models\ShopeePendapatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                        'shopee_pesanan.return_qty'
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
}
