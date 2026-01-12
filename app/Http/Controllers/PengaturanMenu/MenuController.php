<?php

namespace App\Http\Controllers\PengaturanMenu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Menu\CreateRequest;
use App\Http\Requests\Menu\UpdateRequest;
use App\Http\Services\MenuService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct(
        protected MenuService $menu_service
    ) {}

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
            $data = $request->validated();

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
}
