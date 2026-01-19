<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Mapper\PaketMapper;
use App\Http\Requests\Paket\CreateRequest;
use App\Http\Requests\Paket\UpdateRequest;
use App\Http\Services\BarangService;
use App\Http\Services\PaketService;
use App\Http\Services\SellerService;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function __construct(
        protected BarangService $barang_service,
        protected PaketService $paket_service,
        protected SellerService $seller_service
    ) {}

    public function index()
    {
        $data["paket"] = $this->paket_service->list();

        return view("pages.modules.paket.index", $data);
    }

    public function create()
    {
        $data["seller"] = $this->seller_service->list_seller();
        $data["barangs"] = $this->barang_service->list_barang_sku();

        return view("pages.modules.paket.create", $data);
    }

    public function store(CreateRequest $request)
    {
        try {
            $data = $request->all();

            $this->paket_service->create($data);

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
            $data["seller"] = $this->seller_service->list_seller();
            $data["barangs"] = $this->barang_service->list_barang_sku();
            $data["edit"] = $this->paket_service->edit($id);
            $data["paket"] = PaketMapper::toViewModel($data["edit"]);

            return view("pages.modules.paket.edit", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $this->paket_service->update($id, $request->all());

            return back()->with('success', 'Data berhasil diperbarui');

        } catch (\Throwable $e) {

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->paket_service->delete($id);

            return back()
                ->with('success', 'Data berhasil dihapus');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
