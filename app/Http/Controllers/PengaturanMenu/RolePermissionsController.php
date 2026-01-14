<?php

namespace App\Http\Controllers\PengaturanMenu;

use App\Helpers\AuthDivisi;
use App\Http\Controllers\Controller;
use App\Http\Services\DivisiRoleService;
use App\Http\Services\MenuService;
use App\Http\Services\RolePermissionsService;
use App\Http\Services\RoleService;
use Illuminate\Http\Request;

class RolePermissionsController extends Controller
{
    public function __construct(
        protected DivisiRoleService $divisi_role_service,
        protected MenuService $menu_service,
        protected RolePermissionsService $role_permissions_service
    ) {}

    public function index()
    {
        $data["grouping"] = $this->role_permissions_service->listPermissions();

        return view("pages.modules.kelola-menu.role-permissions.index", $data);
    }

    public function create(Request $request)
    {
        $roleId = $request->get('role_id');

        $data["akses"] = $this->divisi_role_service->list_akses_role_all();
        $data["grouping"] = $this->menu_service->list_grouping();

        $data['selectedPermissions'] = [];

        if ($roleId) {
            $data['selectedPermissions'] =
                $this->role_permissions_service->getSelectedPermissions($roleId);
        }

        $data['roleId'] = $roleId;
        return view("pages.modules.kelola-menu.role-permissions.create", $data);
    }

    public function store(Request $request)
    {
        try {
            $this->role_permissions_service->savePermissions(
                $request->role_id,
                $request->permission_ids ?? [],
                AuthDivisi::id()
            );

            return back()
                ->with('success', 'Data berhasil disimpan');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $this->role_permissions_service->deleteByRoleAndMenu(
                $request->role_id,
                $request->menu_id,
                AuthDivisi::id()
            );

            return back()
                ->with('success', 'Data berhasil dihapus');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
