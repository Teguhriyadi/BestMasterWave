<?php

namespace App\Http\Repositories;

use App\Models\DendaKaryawan;
use App\Models\Divisi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DendaRepository
{
    public function get_all_data()
    {
        return DendaKaryawan::get();
    }

    public function insert_data(array $data): bool
    {
        DB::transaction(function() use ($data) {
            foreach ($data["items"] as $item) {
                DendaKaryawan::create([
                    "karyawan_id" => $data["karyawan_id"],
                    "tanggal_denda" => $data["tanggal_denda"],
                    "jenis_denda_id" => $item["jenis_denda"],
                    "keterangan" => $item["keterangan"],
                    "periode_gaji" => $data["periode_gaji"],
                    "created_by" => Auth::user()->id
                ]);
            }
        });

        return true;
    }

    public function get_data_by_id(string $id)
    {
        return DendaKaryawan::where("id", $id)->first();
    }

    public function update_by_id(string $id, array $data)
    {
        $denda = DendaKaryawan::findOrFail($id);

        $denda->update([
            "karyawan_id" => $data["karyawan_id"],
            "tanggal_denda" => $data["tanggal_denda"],
            "jenis_denda_id" => $data["jenis_denda_id"],
            "keterangan" => $data["keterangan"],
            "periode_gaji" => $data['periode_gaji'],
            "updated_by" => Auth::user()->id
        ]);

        return $denda;
    }

    public function delete_by_id(string $id): void
    {
        $denda = DendaKaryawan::findOrFail($id);
        $denda->delete();
    }
}
