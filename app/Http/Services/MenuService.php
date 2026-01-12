<?php

namespace App\Http\Services;

use App\Helpers\AuthDivisi;
use App\Http\Mapper\MenuMapper;
use App\Http\Repositories\MenuRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MenuService
{
    public function __construct(
        protected MenuRepository $menu_repository
    ) {}

    public function list()
    {
        $menu = $this->menu_repository->get_all_data();

        return MenuMapper::toTable($menu);
    }

    public function list_grouping()
    {
        return $this->menu_repository->get_grouping_menu();
    }

    public function list_menu()
    {
        $menu = $this->menu_repository->get_menu();

        return MenuMapper::toTable($menu);
    }

    public function list_parent()
    {
        return DB::transaction(function() {
            return $this->menu_repository->get_parent();
        });
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->menu_repository->insert_data($data);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->menu_repository->get_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        $data['slug'] = Str::slug($data['nama_menu']);

        return DB::transaction(function () use ($id, $data) {
            return $this->menu_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->menu_repository->delete_by_id($id);
        });
    }

    public function sidebar(string $roleId, string $divisiId)
    {
        return $this->menu_repository->sidebarMenu($roleId, $divisiId);
    }
}
