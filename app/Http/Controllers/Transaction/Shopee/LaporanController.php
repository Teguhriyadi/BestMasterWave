<?php

namespace App\Http\Controllers\Transaction\Shopee;

use App\Http\Controllers\Controller;
use App\Http\Services\SellerService;
use App\Models\ShopeePendapatan;
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
            $filterBy = $request->filter_by ?? 'tanggal_dana_dilepaskan';

            $dari = $request->dari
                ? Carbon::parse($request->dari)->startOfDay()
                : Carbon::now()->subDays(30)->startOfDay();

            $sampai = $request->sampai
                ? Carbon::parse($request->sampai)->endOfDay()
                : Carbon::now();

            $query = ShopeePendapatan::with([
                'pesanan:id,no_pesanan,nomor_referensi_sku,jumlah,harga_modal'
            ]);

            if ($request->nama_seller) {
                $query->where('nama_seller', $request->nama_seller);
            }

            $allowedColumns = ['waktu_pesanan', 'tanggal_dana_dilepaskan'];

            if (in_array($filterBy, $allowedColumns)) {
                $query->whereBetween($filterBy, [$dari, $sampai]);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('nomor_referensi_sku', function ($row) {
                    return $row->pesanan
                        ->pluck('nomor_referensi_sku')
                        ->unique()
                        ->map(fn ($sku) => "<span class='badge badge-info mr-1'>{$sku}</span>")
                        ->implode(' ');
                })
                ->addColumn('jumlah', function ($row) {
                    return $row->pesanan
                        ->pluck('jumlah')
                        ->map(fn ($jumlah) => "<span class='badge badge-info mr-1'>{$jumlah}</span>")
                        ->implode(' ');
                })
                ->addColumn('harga_modal', function ($row) {
                    return $row->pesanan
                        ->pluck('harga_modal')
                        ->map(function ($harga_modal) {
                            $formatted = number_format((float) $harga_modal, 0, ',', '.');
                            return "<span class='badge badge-info mr-1'>{$formatted}</span>";
                        })
                        ->implode(' ');
                })
                ->editColumn('waktu_pesanan', function ($row) {
                    return $row->waktu_pesanan ? Carbon::parse($row->waktu_pesanan)->translatedFormat('d F Y') : '-';
                })
                ->editColumn('tanggal_dana_dilepaskan', function ($row) {
                    return $row->tanggal_dana_dilepaskan ? Carbon::parse($row->tanggal_dana_dilepaskan)->translatedFormat('d F Y') : '-';
                })
                ->editColumn('harga_asli', function ($row) {
                    return 'Rp ' . number_format($row->harga_asli, 0, ',', '.');
                })
                ->editColumn('total_diskon', function ($row) {
                    return 'Rp ' . number_format($row->total_diskon, 0, ',', '.');
                })
                ->editColumn('jumlah_pengembalian', function ($row) {
                    return 'Rp ' . number_format($row->jumlah_pengembalian, 0, ',', '.');
                })
                ->editColumn('diskon', function ($row) {
                    $diskon = $row->diskon_produk_shopee + $row->voucher_penjual + $row->cashback_koin;
                    return 'Rp ' . number_format($diskon, 0, ',', '.');
                })
                ->editColumn('ongkir', function ($row) {
                    $ongkir = $row->ongkir_dibayar + $row->diskon_ongkir_ditanggung + $row->gratis_ongkir_shopee + $row->ongkir_diteruskan_shopee + $row->ongkos_kirim_pengembalian + $row->kembali_ke_biaya_pengiriman + $row->pengembalian_biaya_kirim;
                    return 'Rp ' . number_format($ongkir, 0, ',', '.');
                })
                ->editColumn('biaya', function ($row) {
                    $biaya = $row->biaya_komisi_ams + $row->biaya_administrasi + $row->biaya_layanan + $row->biaya_proses_pesanan + $row->premi + $row->biaya_program_hemat_biaya + $row->biaya_transaksi + $row->biaya_kampanye + $row->bea_masuk_pph;
                    return 'Rp ' . number_format($biaya, 0, ',', '.');
                })
                ->editColumn('total_penghasilan', function ($row) {
                    return 'Rp ' . number_format($row->total_penghasilan, 0, ',', '.');
                })
                ->editColumn('kompensasi', function ($row) {
                    $kompensasi = (int) $row->kode_voucher + $row->kompensasi + $row->promo_gratis_ongkir;
                    return 'Rp ' . number_format($kompensasi, 0, ',', '.');
                })
                ->editColumn('laba_rugi', function ($row) {
                    $totalModal = $row->pesanan->sum('harga_modal');
                    $harga_modal = $row->total_penghasilan - $totalModal;
                    return 'Rp ' . number_format($harga_modal, 0, ',', '.');
                })
                ->rawColumns(['nomor_referensi_sku', 'jumlah', 'harga_modal'])
                ->make(true);
        }

        return view('pages.modules.transaction.shopee.laporan.index', $data);
    }
}
