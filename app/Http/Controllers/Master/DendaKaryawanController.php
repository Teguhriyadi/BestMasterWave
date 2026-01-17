<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Services\DendaService;
use App\Http\Services\JenisDendaService;
use App\Http\Services\KaryawanService;
use Illuminate\Http\Request;

class DendaKaryawanController extends Controller
{
    public function __construct(
        protected KaryawanService $karyawan_service,
        protected JenisDendaService $jenis_denda_service,
        protected DendaService $denda_service
    ) {}

    public function index()
    {
        $data["denda"] = $this->denda_service->list();

        return view("pages.modules.denda.index", $data);
    }

    public function create()
    {
        $data["karyawan"] = $this->karyawan_service->list_karyawan();
        $data["denda"] = $this->jenis_denda_service->list_denda();

        return view("pages.modules.denda.create", $data);
    }

    public function store(Request $request)
    {
        try {
            $this->denda_service->create($request->all());

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
            $data["karyawan"] = $this->karyawan_service->list_karyawan();
            $data["denda"] = $this->jenis_denda_service->list_denda();
            $data["edit"] = $this->denda_service->edit($id);

            return view("pages.modules.denda.edit", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->denda_service->update($id, $request->all());

            return back()->with('success', 'Data berhasil diperbarui');

        } catch (\Throwable $e) {

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->denda_service->delete($id);

            return back()
                ->with('success', 'Data berhasil dihapus');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
