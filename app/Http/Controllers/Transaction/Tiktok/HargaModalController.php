<?php

namespace App\Http\Controllers\Transaction\Tiktok;

use App\Http\Controllers\Controller;
use App\Models\TiktokPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HargaModalController extends Controller
{
    public function index()
    {
        return view("pages.modules.transaction.tiktok.harga-modal.index");
    }

    public function update(Request $request)
    {
        try {

            DB::beginTransaction();

            TiktokPesanan::whereBetween("created_time", [
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
