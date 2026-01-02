<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\CreateRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Http\Services\RoleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $role_service
    ) {}

    public function index()
    {
        $data["role"] = $this->role_service->list();

        return view("pages.modules.role.index", $data);
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->role_service->create($request->validated());

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
            $data["edit"] = $this->role_service->edit($id);

            return view("pages.modules.role.edit", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $this->role_service->update($id, $request->validated());

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
            $this->role_service->delete($id);

            return back()
                ->with('success', 'Data berhasil dihapus');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
