<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use App\Models\ShopeePendapatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class AppController extends Controller
{
    public function dashboard()
    {
        try {

            DB::beginTransaction();

            $data["platform"] = Platform::where("status", "1")->count();
            $data["seller"] = Platform::where("status", "1")->count();
            $data["shopee_pendapatan"] = ShopeePendapatan::count();

            DB::commit();

            return view("pages.modules.dashboard", $data);

        } catch (\Exception $e) {

            DB::rollBack();

            dd($e->getMessage());
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->to("/login")->with("success", "Anda Berhasil Logout");
    }
}
