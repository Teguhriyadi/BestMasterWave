<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SellerController extends Controller
{
    public function index()
    {
        try {

            DB::beginTransaction();

            $data["platform"] = Platform::orderBy("created_at", "DESC")->get();
            $data["seller"] = Seller::with([
                "platform:id,nama"
            ])->orderBy("created_at", "DESC")->get();

            DB::commit();

            return view("pages.modules.seller.v_index", $data);
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->to("/admin-panel/dashboard")->with("error", $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {

            DB::beginTransaction();

            Seller::create([
                "platform_id" => $request->platform_id,
                "nama" => $request->seller,
                "slug" => Str::slug($request->seller),
            ]);

            DB::commit();

            return back()->with("success", "Data Berhasil di Tambahkan");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }
}
