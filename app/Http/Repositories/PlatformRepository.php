<?php

namespace App\Http\Repositories;

use App\Helpers\AuthDivisi;
use App\Models\Platform;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PlatformRepository
{
    public function get_all_data()
    {
        if (empty(Auth::user()->one_divisi_roles)) {
            return Platform::orderBy("created_at", "DESC")->get();
        } else {
            return Platform::get();
        }
    }

    public function insert_data(array $data)
    {
        $platform = Platform::create([
            "nama" => $data["platform"],
            "slug" => Str::slug($data["platform"])
        ]);

        return $platform;
    }

    public function get_data_by_id(string $id)
    {
        return Platform::where("id", $id)->first();
    }

    public function update_by_id(string $id, array $data)
    {
        $platform = Platform::findOrFail($id);

        $platform->update([
            "nama" => $data["platform"],
            "slug"        => Str::slug($data["platform"])
        ]);

        return $platform;
    }

    public function delete_by_id(string $id): void
    {
        $platform = Platform::findOrFail($id);
        $platform->delete();
    }
}
