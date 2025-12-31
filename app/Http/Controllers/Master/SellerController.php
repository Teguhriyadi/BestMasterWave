<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Services\SellerService;
use App\Models\Platform;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellerController extends Controller
{
    public function __construct(
        protected SellerService $seller_service
    ) {}

    public function index()
    {
        try {

            DB::beginTransaction();

            $data["platform"] = Platform::orderBy("created_at", "DESC")->get();
            $data["seller"] = $this->seller_service->list();

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
            $this->seller_service->create($request->all());

            return back()
                ->with('success', 'Data berhasil disimpan');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $data["platform"] = Platform::orderBy("created_at", "DESC")->get();
            $data["edit"] = $this->seller_service->edit($id);

            return view("pages.modules.seller.v_edit", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->seller_service->update($id, $request->all());

            return back()->with('success', 'Data berhasil diperbarui');

        } catch (ModelNotFoundException $e) {

            return back()->with('error', 'Seller tidak ditemukan');

        } catch (\Throwable $e) {

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->seller_service->delete($id);

            return back()
                ->with('success', 'Data berhasil dihapus');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
