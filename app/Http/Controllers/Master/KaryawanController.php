<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Karyawan\CreateRequest;
use App\Http\Requests\Karyawan\UpdateRequest;
use App\Http\Services\BankService;
use App\Http\Services\JabatanService;
use App\Http\Services\KaryawanService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function __construct(
        protected BankService $bank_service,
        protected JabatanService $jabatan_service,
        protected KaryawanService $karyawan_service
    ) {}

    public function index()
    {
        $data["karyawan"] = $this->karyawan_service->list();

        return view("pages.modules.karyawan.index", $data);
    }

    public function create()
    {
        $data["bank"] = $this->bank_service->list();
        $data["jabatan"] = $this->jabatan_service->list();

        return view("pages.modules.karyawan.create", $data);
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->karyawan_service->create($request->all());

            return back()
                ->with('success', 'Data berhasil disimpan');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $data["edit"] = $this->karyawan_service->edit($id);

            return view("pages.modules.karyawan.detail", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function lihat_log($id)
    {
        try {
            $data["log"] = $this->karyawan_service->show_log($id);

            return view("pages.modules.karyawan.lihat-log", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $data["bank"] = $this->bank_service->list();
            $data["jabatan"] = $this->jabatan_service->list();
            $data["edit"] = $this->karyawan_service->edit($id);

            return view("pages.modules.karyawan.edit", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $this->karyawan_service->update($id, $request->all());

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
            $this->karyawan_service->delete($id);

            return back()
                ->with('success', 'Data berhasil dihapus');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
