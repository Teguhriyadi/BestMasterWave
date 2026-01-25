<?php

namespace App\Http\Controllers\Master;

use App\Helpers\AuthDivisi;
use App\Http\Controllers\Controller;
use App\Http\Requests\Barang\CreateRequest;
use App\Http\Requests\Barang\UpdateRequest;
use App\Http\Services\BarangService;
use App\Http\Services\SellerService;
use App\Models\Barang;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;

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

    public function update(UpdateRequest $request, $id)
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

    public function upload()
    {
        return view("pages.modules.barang.upload");
    }

    public function process_upload(Request $request)
    {
        $request->validate([
            'file' => ['required', 'mimes:xlsx,xls']
        ]);

        $spreadsheet = IOFactory::load($request->file('file')->getPathname());
        $sheet = $spreadsheet->getSheetByName('Modal SKU Tunggal');

        if (!$sheet) {
            return back()->withErrors('Sheet "Modal SKU Tunggal" tidak ditemukan');
        }

        $rows = $sheet->toArray(null, true, true, false);

        $headerRow = null;
        for ($i = 0; $i < 10; $i++) {
            foreach ($rows[$i] ?? [] as $cell) {
                if (stripos((string) $cell, 'sku') !== false) {
                    $headerRow = $i;
                    break 2;
                }
            }
        }

        if ($headerRow === null) {
            return back()->withErrors('Header SKU tidak ditemukan');
        }

        $headers = array_map(
            fn($h) => strtolower(trim((string) $h)),
            $rows[$headerRow]
        );

        $skuIndex = array_search(
            true,
            array_map(fn($h) => str_contains($h, 'sku'), $headers),
            true
        );

        $hargaModalIndex = array_search(
            true,
            array_map(fn($h) => str_contains($h, 'harga') && str_contains($h, 'modal'), $headers),
            true
        );

        if ($skuIndex === false) {
            return back()->withErrors('Kolom SKU tidak ditemukan');
        }

        if ($hargaModalIndex === false) {
            return back()->withErrors('Kolom Harga Modal tidak ditemukan');
        }

        $data = [];
        $seenSku = [];

        foreach (array_slice($rows, $headerRow + 1) as $row) {
            $sku = trim((string) ($row[$skuIndex] ?? ''));

            if ($sku === '' || isset($seenSku[$sku])) {
                continue;
            }

            $seenSku[$sku] = true;

            $hargaModalRaw = $row[$hargaModalIndex] ?? 0;

            $hargaModal = (int) str_replace(
                [',', '.', ' '],
                '',
                (string) $hargaModalRaw
            );

            $data[] = [
                'id' => \Illuminate\Support\Str::uuid(),
                'sku_barang' => $sku,
                'harga_modal' => $hargaModal,
                'created_by' => Auth::id(),
                'divisi_id' => AuthDivisi::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Barang::upsert(
            $data,
            ['sku_barang'],
            []
        );

        return back()->with('success', 'Import SKU berhasil');
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
