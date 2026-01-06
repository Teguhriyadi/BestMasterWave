<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Barang\CreateRequest;
use App\Http\Services\BarangService;
use App\Http\Services\SellerService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    public function __construct(
        protected SellerService $seller_service,
        protected BarangService $barang_service
    ) {}

    public function index()
    {
        $data["seller"] = $this->seller_service->list_seller_by_divisi();

        $data["barang"] = $this->barang_service->list();

        if (!empty(Auth::user()->one_divisi_roles)) {
            if ($data["seller"]->count() == 0) {
                return redirect()->to("/admin-panel/seller")->with("error", "Data Seller Tidak Ada");
            }
        }

        return view("pages.modules.barang.index", $data);
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->barang_service->create($request->all());

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
            $data["seller"] = $this->seller_service->list();
            $data["barang"] = $this->barang_service->list();
            $data["edit"] = $this->barang_service->edit($id);

            return view("pages.modules.barang.edit", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->barang_service->update($id, $request->all());

            return back()->with('success', 'Data berhasil diperbarui');

        } catch (ModelNotFoundException $e) {

            return back()->with('error', 'Supplier tidak ditemukan');

        } catch (\Throwable $e) {

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->barang_service->delete($id);

            return back()
                ->with('success', 'Data berhasil dihapus');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
