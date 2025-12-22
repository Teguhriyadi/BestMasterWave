<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PlatformController extends Controller
{
    public function index()
    {
        try {

            DB::beginTransaction();

            $data["platform"] = Platform::orderBy("created_at", "DESC")->get();

            DB::commit();

            return view("pages.modules.platform.v_index", $data);

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->to("/admin-panel/dashboard")->with("error", $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {

            DB::beginTransaction();

            Platform::create([
                "nama" => $request->platform,
                "slug" => Str::slug($request->platform),
            ]);

            DB::commit();

            return back()->with("success", "Data Berhasil di Tambahkan");

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }
}
