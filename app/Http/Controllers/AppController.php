<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Platform;
use App\Models\ShopeePendapatan;
use App\Models\ShopeePesanan;
use App\Models\Supplier;
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
            $data["supplier"] = Supplier::count();
            $data["karyawan"] = Karyawan::count();

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
