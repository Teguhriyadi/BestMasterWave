<?php

namespace App\Http\Controllers\Master;

use App\Helpers\AuthDivisi;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CreateRequest;
use App\Http\Requests\Users\UpdateRequest;
use App\Http\Services\DivisiRoleService;
use App\Http\Services\DivisiService;
use App\Http\Services\UsersService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDivisiRoleController extends Controller
{
    public function __construct(
        protected UsersService $users_service,
        protected DivisiService $divisi_service,
        protected DivisiRoleService $divisi_role_service
    ) {}

    public function index()
    {
        if (empty(Auth::user()->one_divisi_roles)) {
            $data["users"] = $this->users_service->list();
        } else {
            $data["users"] = $this->divisi_role_service->list_users();
        }

        return view("pages.modules.users.index", $data);
    }

    public function create()
    {
        $data["divisi"] = $this->divisi_service->list();

        return view("pages.modules.users.create", $data);
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->users_service->create($request->all());

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
            $users = $this->users_service->edit($id);
            $data["edit"] = $users;

            if (empty(Auth::user()->one_divisi_roles)) {
                $data["selectedRoles"] = $users->divisiRoles->pluck("role_id")->toArray();
                $data["selectedDivisi"] = $users->divisiRoles->first()?->divisi_id;
            } else {
                $data["selectedDivisi"] = AuthDivisi::id();
            }

            return view("pages.modules.users.edit", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $this->users_service->update($id, $request->all());

            return back()->with('success', 'Data berhasil diperbarui');

        } catch (ModelNotFoundException $e) {

            return back()->with('error', 'Users tidak ditemukan');

        } catch (\Throwable $e) {

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->users_service->delete($id);

            return back()
                ->with('success', 'Data berhasil dihapus');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function change_status($id)
    {
        try {
            $this->users_service->toggleStatus($id);

            return back()
                ->with('success', 'Data berhasil disimpan');

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function detail($id)
    {
        try {
            $data["detail"] = $this->users_service->edit($id);

            return view("pages.modules.users.detail", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
