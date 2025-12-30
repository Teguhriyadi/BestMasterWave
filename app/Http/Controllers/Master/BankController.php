<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Services\BankService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function __construct(
        protected BankService $bank_service
    ) {}

    public function index()
    {
        $data["bank"] = $this->bank_service->list();

        return view("pages.modules.bank.index", $data);
    }

    public function store(Request $request)
    {
        try {
            $this->bank_service->create($request->all());

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
            $data["edit"] = $this->bank_service->edit($id);

            return view("pages.modules.supplier.edit", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->bank_service->update($id, $request->all());

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
            $this->bank_service->delete($id);

            return back()
                ->with('success', 'Data berhasil dihapus');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
