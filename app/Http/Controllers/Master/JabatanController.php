<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Jabatan\CreateRequest;
use App\Http\Requests\Jabatan\UpdateRequest;
use App\Http\Services\JabatanService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function __construct(
        protected JabatanService $jabatan_service
    ) {}

    public function index()
    {
        $data["jabatan"] = $this->jabatan_service->list();

        return view("pages.modules.jabatan.index", $data);
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->jabatan_service->create($request->validated());

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
            $data["edit"] = $this->jabatan_service->edit($id);

            return view("pages.modules.jabatan.edit", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $this->jabatan_service->update($id, $request->validated());

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
            $this->jabatan_service->delete($id);

            return back()
                ->with('success', 'Data berhasil dihapus');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
