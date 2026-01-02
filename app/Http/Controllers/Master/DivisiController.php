<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Divisi\CreateRequest;
use App\Http\Requests\Divisi\UpdateRequest;
use App\Http\Services\DivisiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    public function __construct(
        protected DivisiService $divisi_service
    ) {}

    public function index()
    {
        $data["divisi"] = $this->divisi_service->list();

        return view("pages.modules.divisi.index", $data);
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->divisi_service->create($request->validated());

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
            $data["edit"] = $this->divisi_service->edit($id);

            return view("pages.modules.divisi.edit", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $this->divisi_service->update($id, $request->validated());

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
            $this->divisi_service->delete($id);

            return back()
                ->with('success', 'Data berhasil dihapus');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
