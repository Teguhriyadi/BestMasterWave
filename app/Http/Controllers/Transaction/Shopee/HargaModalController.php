<?php

namespace App\Http\Controllers\Transaction\Shopee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shopee\HargaModal\CreateRequest;
use App\Models\ShopeePesanan;
use Illuminate\Support\Facades\DB;

class HargaModalController extends Controller
{
    public function index()
    {
        return view("pages.modules.transaction.shopee.harga-modal.index");
    }

    public function update(CreateRequest $request)
    {
        try {

            DB::beginTransaction();

            ShopeePesanan::whereBetween("waktu_pesanan_dibuat", [
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
