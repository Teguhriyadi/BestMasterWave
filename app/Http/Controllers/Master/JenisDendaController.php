<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\JenisDenda\CreateRequest;
use App\Http\Requests\JenisDenda\UpdateRequest;
use App\Http\Services\JenisDendaService;
use Illuminate\Http\Request;

class JenisDendaController extends Controller
{
    public function __construct(
        protected JenisDendaService $jenis_denda_service
    ) {}

    public function index()
    {
        $data["jenis_denda"] = $this->jenis_denda_service->list();

        return view("pages.modules.jenis-denda.index", $data);
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->jenis_denda_service->create($request->all());

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
            $data["edit"] = $this->jenis_denda_service->edit($id);

            return view("pages.modules.jenis-denda.edit", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $data = $request->validated();

            $this->jenis_denda_service->update($id, $data);

            return back()->with('success', 'Data berhasil diperbarui');

        } catch (\Throwable $e) {

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->jenis_denda_service->delete($id);

            return back()
                ->with('success', 'Data berhasil dihapus');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
