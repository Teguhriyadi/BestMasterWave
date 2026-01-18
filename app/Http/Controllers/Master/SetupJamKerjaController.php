<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetupJamKerja\CreateRequest;
use App\Http\Requests\SetupJamKerja\UpdateRequest;
use App\Http\Services\DivisiService;
use App\Http\Services\SetupJamKerjaService;
use Illuminate\Http\Request;

class SetupJamKerjaController extends Controller
{
    public function __construct(
        protected DivisiService $divisi_service,
        protected SetupJamKerjaService $setup_jam_kerja_service
    ) {}

    public function index()
    {
        $data["divisi"] = $this->divisi_service->list();
        $data["setup_jam_kerja"] = $this->setup_jam_kerja_service->list();

        return view("pages.modules.setup-jam-kerja.index", $data);
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->setup_jam_kerja_service->create($request->all());

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
            $data["divisi"] = $this->divisi_service->list();
            $data["edit"] = $this->setup_jam_kerja_service->edit($id);

            return view("pages.modules.setup-jam-kerja.edit", $data);
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

            $this->setup_jam_kerja_service->update($id, $data);

            return back()->with('success', 'Data berhasil diperbarui');

        } catch (\Throwable $e) {

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->setup_jam_kerja_service->delete($id);

            return back()
                ->with('success', 'Data berhasil dihapus');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
