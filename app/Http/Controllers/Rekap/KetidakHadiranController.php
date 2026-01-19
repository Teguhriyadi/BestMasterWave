<?php

namespace App\Http\Controllers\Rekap;

use App\Http\Controllers\Controller;
use App\Http\Requests\KetidakHadiran\CreateRequest;
use App\Http\Requests\KetidakHadiran\UpdateRequest;
use App\Http\Services\KaryawanService;
use App\Http\Services\KetidakHadiranService;
use Illuminate\Http\Request;

class KetidakHadiranController extends Controller
{
    public function __construct(
        protected KetidakHadiranService $ketidak_hadiran_service,
        protected KaryawanService $karyawan_service
    ) {}

    public function index()
    {
        $data["absensi"] = $this->ketidak_hadiran_service->list();
        $data["karyawan"] = $this->karyawan_service->list_karyawan();

        return view("pages.modules.rekap.ketidakhadiran.index", $data);
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->ketidak_hadiran_service->create($request->all());

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
            $data["edit"] = $this->ketidak_hadiran_service->edit($id);

            return view("pages.modules.rekap.ketidakhadiran.edit", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $this->ketidak_hadiran_service->update($id, $request->all());

            return back()->with('success', 'Data berhasil diperbarui');

        } catch (\Throwable $e) {

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->ketidak_hadiran_service->delete($id);

            return back()
                ->with('success', 'Data berhasil dihapus');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function ubah_status($id)
    {
        try {
            $data["edit"] = $this->ketidak_hadiran_service->edit($id);

            return view("pages.modules.rekap.ketidakhadiran.ubah-status", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function update_status_terbaru(Request $request, $id)
    {
        try {
            $this->ketidak_hadiran_service->update_status($id, $request->all());

            return back()->with('success', 'Data berhasil diperbarui');

        } catch (\Throwable $e) {

            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
