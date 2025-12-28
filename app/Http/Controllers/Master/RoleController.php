<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $role
    ) {}

    public function index()
    {
        $data["role"] = $this->role->get_all_data();

        return view("pages.modules.role.index", $data);
    }

    public function store(Request $request)
    {
        $this->role->create($request->all());

        return back()->with("success", "Data Berhasil di Tambahkan");
    }
}
