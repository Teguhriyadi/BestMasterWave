<?php

namespace App\Http\Controllers\Transaction\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pembelian\CreateRequest;
use App\Http\Services\BarangService;
use App\Http\Services\PembelianService;
use App\Http\Services\SupplierService;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    public function __construct(
        protected BarangService $barang_service,
        protected SupplierService $supplier_service,
        protected PembelianService $pembelian_service
    ) {}

    public function index(Request $request)
    {
        $data["pembelian"] = $this->pembelian_service->list($request);
        $data["supplier"] = $this->supplier_service->list();

        return view("pages.modules.transaction.invoice.pembelian.index", $data);
    }

    public function create()
    {
        $data["barang"] = $this->barang_service->list_barang_sku();
        $data["supplier"] = $this->supplier_service->list_supplier_data();

        if ($data["barang"]->count() == 0) {
            return redirect()->to("/admin-panel/barang")->with("error", "Data Barang Tidak Ada");
        }

        if ($data["supplier"]->count() == 0) {
            return redirect()->to("/admin-panel/supplier")->with("error", "Data Supplier Tidak Ada");
        }

        return view("pages.modules.transaction.invoice.pembelian.create", $data);
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->pembelian_service->create($request->all());

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
        $data["barang"] = $this->barang_service->list_barang_sku();
        $data["supplier"] = $this->supplier_service->list_supplier_data();

        if ($data["barang"]->count() == 0) {
            return redirect()->to("/admin-panel/barang")->with("error", "Data Barang Tidak Ada");
        }

        if ($data["supplier"]->count() == 0) {
            return redirect()->to("/admin-panel/supplier")->with("error", "Data Supplier Tidak Ada");
        }

        $data["edit"] = $this->pembelian_service->edit($id);

        return view("pages.modules.transaction.invoice.pembelian.edit", $data);
    }

    public function update(Request $request, $id)
    {
        try {
            $this->pembelian_service->update($id, $request->all());

            return back()
                ->with('success', 'Data berhasil disimpan');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->pembelian_service->delete($id);

            return back()
                ->with('success', 'Data berhasil dihapus');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
