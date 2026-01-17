<?php

namespace App\Http\Controllers\PengaturanMenu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Menu\CreateRequest;
use App\Http\Requests\Menu\UpdateRequest;
use App\Http\Services\MenuService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function __construct(
        protected MenuService $menu_service
    ) {
        if (!empty(Auth::user()->one_divisi_roles)) {
            abort(403);
        }
    }

    public function index()
    {
        $data["menu"] = $this->menu_service->list();
        $data["headers"] = $this->menu_service->list_parent_header();
        $data["parents"] = $this->menu_service->list_parent();

        return view("pages.modules.kelola-menu.menu.index", $data);
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->menu_service->create($request->all());

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
            $data["headers"] = $this->menu_service->list_parent_header();
            $data["parents"] = $this->menu_service->list_parent();
            $data["edit"] = $this->menu_service->edit($id);

            return view("pages.modules.kelola-menu.menu.edit", $data);
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

            $this->menu_service->update($id, $data);

            return back()->with('success', 'Data berhasil diperbarui');
        } catch (\Throwable $e) {

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->menu_service->delete($id);

            return back()
                ->with('success', 'Data berhasil dihapus');
        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function move($id, $direction)
    {
        try {
            $this->menu_service->move($id, $direction);
            return back()->with('success', 'Urutan berhasil diubah');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
