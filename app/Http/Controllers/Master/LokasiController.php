<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lokasi\CreateRequest;
use App\Http\Requests\Lokasi\UpdateRequest;
use App\Http\Services\LokasiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function __construct(
        protected LokasiService $lokasi_service
    ) {}

    public function index()
    {
        $data["lokasi"] = $this->lokasi_service->list();

        return view("pages.modules.lokasi.index", $data);
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->lokasi_service->create($request->all());

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
            $data["edit"] = $this->lokasi_service->edit($id);

            return view("pages.modules.lokasi.edit", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $data = $request->all();

            $this->lokasi_service->update($id, $data);

            return back()->with('success', 'Data berhasil diperbarui');

        } catch (\Throwable $e) {

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->lokasi_service->delete($id);

            return back()
                ->with('success', 'Data berhasil dihapus');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
