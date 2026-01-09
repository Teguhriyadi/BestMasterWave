<?php

namespace App\Http\Repositories;

use App\Models\Jabatan;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class KaryawanRepository
{
    public function get_all_data()
    {
        return Karyawan::orderBy("created_at", "DESC")->get();
    }

    public function insert_data(array $data)
    {
        $karyawan = Karyawan::create([
            "id_fp" => $data["id_sidik_jari"],
            "no_ktp" => $data["no_ktp"],
            "no_kk" => $data["no_kk"],
            "no_bpjs_kesehatan" => $data["no_bpjs_kesehatan"],
            "nama"  => $data["nama"],
            "nama_panggilan" => $data["nama_panggilan"],
            "tanggal_masuk" => $data["tanggal_masuk"],
            "no_hp" => $data["no_hp"],
            "no_hp_darurat" => $data["no_hp_darurat"],
            "tempat_lahir" => $data["tempat_lahir"],
            "tanggal_lahir" => $data["tanggal_lahir"],
            "jenis_kelamin" => $data["jenis_kelamin"],
            "alamat" => $data["alamat"],
            "status_pernikahan" => $data["status_pernikahan"],
            "bank_id" => $data["bank_id"],
            "acc_no" => $data["acc_no"],
            "acc_name" => $data["acc_name"],
            "created_by" => Auth::user()->id,
            "jabatan_id" => $data["jabatan_id"]
        ]);

        return $karyawan;
    }

    public function get_data_by_id(string $id)
    {
        return Karyawan::where("id", $id)->first();
    }

    public function update_by_id(string $id, array $data)
    {
        $karyawan = Karyawan::findOrFail($id);

        $karyawan->update([
            "id_fp" => $data["id_sidik_jari"],
            "no_ktp" => $data["no_ktp"],
            "no_kk" => $data["no_kk"],
            "no_bpjs_kesehatan" => $data["no_bpjs_kesehatan"],
            "nama"  => $data["nama"],
            "nama_panggilan" => $data["nama_panggilan"],
            "tanggal_masuk" => $data["tanggal_masuk"],
            "no_hp" => $data["no_hp"],
            "no_hp_darurat" => $data["no_hp_darurat"],
            "tempat_lahir" => $data["tempat_lahir"],
            "tanggal_lahir" => $data["tanggal_lahir"],
            "jenis_kelamin" => $data["jenis_kelamin"],
            "alamat" => $data["alamat"],
            "status_pernikahan" => $data["status_pernikahan"],
            "bank_id" => $data["bank_id"],
            "acc_no" => $data["acc_no"],
            "acc_name" => $data["acc_name"],
            "updated_by" => Auth::user()->id,
            "jabatan_id" => $data["jabatan_id"]
        ]);

        return $karyawan;
    }

    public function delete_by_id(string $id): void
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->delete();
    }
}
