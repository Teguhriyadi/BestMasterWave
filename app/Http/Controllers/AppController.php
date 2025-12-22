<?php

namespace App\Http\Controllers;

use App\Excel\IncomeDataReadFilter;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Excel\IncomeHeaderReadFilter;
use App\Models\Platform;
use App\Models\UploadsData;
use App\Models\UploadsFile;
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
            $data["banyak_upload"] = UploadsFile::count();

            DB::commit();

            return view("pages.modules.dashboard", $data);

        } catch (\Exception $e) {

            DB::rollBack();

            dd($e->getMessage());
        }
    }
}
