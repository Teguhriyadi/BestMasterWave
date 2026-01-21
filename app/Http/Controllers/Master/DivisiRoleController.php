<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Services\DivisiRoleService;
use App\Http\Services\DivisiService;
use App\Http\Services\RoleService;
use Illuminate\Http\Request;

class DivisiRoleController extends Controller
{
    public function __construct(
        protected DivisiService $divisi_service,
        protected RoleService $role_service,
        protected DivisiRoleService $divisi_role_service
    ) {}

    public function index()
    {
        $data["divisi_role"] = $this->divisi_role_service->list();

        return view("pages.modules.divisi-role.index", $data);
    }

    public function create()
    {
        $data["divisi"] = $this->divisi_service->list();
        $data["role"] = $this->role_service->list();

        if ($data["role"]->count() == 0) {
            return redirect()->to("/admin-panel/role")->with("error", " Data Role Tidak Ada. Silahkan Buat Terlebih Dahulu");
        }

        if ($data["divisi"]->count() == 0) {
            return redirect()->to("/admin-panel/divisi")->with("error", " Data Divisi Tidak Ada. Silahkan Buat Terlebih Dahulu");
        }

        return view("pages.modules.divisi-role.create", $data);
    }

    public function store(Request $request)
    {
        try {
            $this->divisi_role_service->create($request->all());

            return redirect()->to("/admin-panel/divisi-role")
                ->with('success', 'Data berhasil disimpan');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function getRoles(string $divisiId)
    {
        return response()->json([
            'status' => true,
            'roles'  => $this->divisi_role_service->getRolesByDivision($divisiId),
        ]);
    }
}
