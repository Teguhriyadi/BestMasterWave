<?php

namespace App\Http\Controllers\Transaction\Shopee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shopee\HargaModal\CreateRequest;
use App\Http\Services\BarangService;
use App\Models\Barang;
use App\Models\ShopeePesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HargaModalController extends Controller
{
    public function __construct(
        protected BarangService $barang_service
    ) {}

    public function index()
    {
        try {

            DB::beginTransaction();

            $data["barang"] = $this->barang_service->list();

            DB::commit();

            return view("pages.modules.transaction.shopee.harga-modal.index", $data);

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function get_harga_modal(Request $request)
    {
        $barang = Barang::find($request->sku_id);

        if (!$barang) {
            return response()->json([
                'status' => false,
                'harga_modal' => 0
            ]);
        }

        return response()->json([
            'status' => true,
            'harga_modal' => $barang->harga_modal
        ]);
    }

    public function update(CreateRequest $request)
    {
        try {

            DB::beginTransaction();

            ShopeePesanan::where("nomor_referensi_sku", $request->sku_barang)
            ->whereBetween("waktu_pesanan_dibuat", [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59'
            ])->update([
                "harga_modal" => $request->harga_modal
            ]);

            DB::commit();

            return back()->with("success", "Data Berhasil di Simpan");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }
}
