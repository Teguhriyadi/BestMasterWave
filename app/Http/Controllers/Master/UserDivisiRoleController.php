<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CreateRequest;
use App\Http\Requests\Users\UpdateRequest;
use App\Http\Services\DivisiService;
use App\Http\Services\UsersService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserDivisiRoleController extends Controller
{
    public function __construct(
        protected UsersService $users_service,
        protected DivisiService $divisi_service
    ) {}

    public function index()
    {
        $data["users"] = $this->users_service->list();

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
            $data["selectedRoles"] = $users->divisiRoles->pluck("role_id")->toArray();
            $data["selectedDivisi"] = $users->divisiRoles->first()?->divisi_id;

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
            $this->users_service->update($id, $request->validated());

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
}
