<?php

namespace App\Http\Controllers\PengaturanMenu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permissions\CreateRequest;
use App\Http\Requests\Permissions\UpdateRequest;
use App\Http\Services\MenuService;
use App\Http\Services\PermissionsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionsController extends Controller
{
    public function __construct(
        protected MenuService $menu_service,
        protected PermissionsService $permissions_service
    ) {
        if (!empty(Auth::user()->one_divisi_roles)) {
            abort(403);
        }
    }

    public function index()
    {
        $data["menu"] = $this->menu_service->list_menu();
        $data["permissions"] = $this->permissions_service->list();

        return view("pages.modules.kelola-menu.permissions.index", $data);
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->permissions_service->create($request->all());

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
            $data["menu"] = $this->menu_service->list_menu();
            $data["permissions"] = $this->permissions_service->list();
            $data["edit"] = $this->permissions_service->edit($id);

            $akses_nama_full = $data["edit"]["akses"];
            $explode = explode('.', $akses_nama_full);

            $data['akses_nama'] = $explode[0];
            $data["tipe_akses"] = $explode[1];

            return view("pages.modules.kelola-menu.permissions.edit", $data);
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

            $this->permissions_service->update($id, $data);

            return back()->with('success', 'Data berhasil diperbarui');

        } catch (\Throwable $e) {

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->permissions_service->delete($id);

            return back()
                ->with('success', 'Data berhasil dihapus');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
