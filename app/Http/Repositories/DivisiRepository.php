<?php

namespace App\Http\Repositories;

use App\Helpers\AuthDivisi;
use App\Models\Divisi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DivisiRepository
{
    public function get_all_data()
    {
        if (empty(Auth::user()->one_divisi_roles)) {
            return Divisi::orderBy("created_at", "DESC")->get();
        } else {
            return Divisi::where("id", AuthDivisi::id())->get();
        }
    }

    public function insert_data(array $data)
    {
        $divisi = Divisi::create([
            "nama_divisi" => $data["nama_divisi"],
            "slug" => Str::slug($data["nama_divisi"])
        ]);

        return $divisi;
    }

    public function get_data_by_id(string $id)
    {
        return Divisi::where("id", $id)->first();
    }

    public function update_by_id(string $id, array $data)
    {
        $divisi = Divisi::findOrFail($id);

        $divisi->update([
            "nama_divisi" => $data["nama_divisi"],
            "slug"        => Str::slug($data["nama_divisi"])
        ]);

        return $divisi;
    }

    public function delete_by_id(string $id): void
    {
        $supplier = Divisi::findOrFail($id);
        $supplier->delete();
    }

    public function getRolesByDivisi(string $divisionId)
    {
        return Divisi::with([
            'roles' => function ($q) use ($divisionId) {
                $q->select('id', 'nama_role', 'divisi_id')
                  ->where('divisi_id', AuthDivisi::id());
            }
        ])
        ->findOrFail($divisionId)
        ->roles;
    }
}
