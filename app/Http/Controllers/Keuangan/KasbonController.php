<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Http\Services\KaryawanService;
use App\Http\Services\KasbonService;
use App\Models\Kasbon;
use Illuminate\Http\Request;

class KasbonController extends Controller
{
    public function __construct(
        protected KaryawanService $karyawan_service,
        protected KasbonService $kasbon_service
    ) {}

    public function index()
    {
        $data["karyawan"] = $this->karyawan_service->list_karyawan();
        $data["kasbon"] = $this->kasbon_service->list();

        return view("pages.modules.kasbon.index", $data);
    }

    public function store(Request $request)
    {
        try {
            $this->kasbon_service->create($request->all());

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
            $data["edit"] = $this->kasbon_service->edit($id);

            return view("pages.modules.kasbon.edit", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();

            $this->kasbon_service->update($id, $data);

            return back()->with('success', 'Data berhasil diperbarui');

        } catch (\Throwable $e) {

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function topup(Request $request, Kasbon $kasbon)
    {
        try {
            $this->kasbon_service->topUp($kasbon, $request->only([
                'nominal', 'tanggal', 'keterangan'
            ]));

            return back()->with('success', 'Top up kasbon berhasil');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function bayar(Request $request, Kasbon $kasbon)
    {
        try {
            $this->kasbon_service->bayar($kasbon, $request->only([
                'nominal', 'tanggal', 'metode', 'keterangan'
            ]));

            return back()->with('success', 'Pembayaran kasbon berhasil');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $data["kasbon"] = $this->kasbon_service->detail($id);

            return view("pages.modules.kasbon.detail", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->kasbon_service->delete($id);

            return back()
                ->with('success', 'Data berhasil dihapus');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
