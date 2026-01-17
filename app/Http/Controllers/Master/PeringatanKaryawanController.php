<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Peringatan\CreateRequest;
use App\Http\Requests\Peringatan\UpdateRequest;
use App\Http\Services\JenisPeringatanService;
use App\Http\Services\KaryawanService;
use App\Http\Services\PeringatanService;
use Illuminate\Http\Request;

class PeringatanKaryawanController extends Controller
{
    public function __construct(
        protected KaryawanService $karyawan_service,
        protected PeringatanService $peringatan_service,
        protected JenisPeringatanService $jenis_peringatan_service
    ) {}

    public function index()
    {
        $data["karyawan"] = $this->karyawan_service->list_karyawan();
        $data["peringatan"] = $this->peringatan_service->list();
        $data["jenis_peringatan"] = $this->jenis_peringatan_service->list_peringatan();

        return view("pages.modules.peringatan.index", $data);
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->peringatan_service->create($request->all());

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
            $data["edit"] = $this->peringatan_service->edit($id);
            $data["jenis_peringatan"] = $this->jenis_peringatan_service->list_peringatan();

            return view("pages.modules.peringatan.edit", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function ubah_status($id)
    {
        try {
            $data["edit"] = $this->peringatan_service->edit($id);

            return view("pages.modules.peringatan.ubah-status", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function update_status(Request $request, $id)
    {
        try {
            $data = $request->all();

            $this->peringatan_service->update_status($id, $data);

            return back()->with('success', 'Data berhasil diperbarui');

        } catch (\Throwable $e) {

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $data = $request->validated();

            $this->peringatan_service->update($id, $data);

            return back()->with('success', 'Data berhasil diperbarui');

        } catch (\Throwable $e) {

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->peringatan_service->delete($id);

            return back()
                ->with('success', 'Data berhasil dihapus');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
