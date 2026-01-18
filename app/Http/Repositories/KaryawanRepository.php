<?php

namespace App\Http\Repositories;

use App\Helpers\AuthDivisi;
use App\Models\DendaKaryawan;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Kasbon;
use App\Models\LogKaryawan;
use App\Models\PeringatanKaryawan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class KaryawanRepository
{
    public function get_all_data()
    {
        return Karyawan::orderBy("created_at", "DESC")->get();
    }

    public function get_list_karyawan()
    {
        return Karyawan::where("divisi_id", AuthDivisi::id())
            ->get();
    }

    public function get_denda_karyawan_by_id(string $karyawan_id)
    {
        return DendaKaryawan::where("karyawan_id", $karyawan_id)
            ->where("status", "Disetujui")
            ->get();
    }

    public function get_pelanggaran_karyawan_by_id(string $karyawan_id)
    {
        return PeringatanKaryawan::where("karyawan_id", $karyawan_id)
            ->where("status", "Aktif")
            ->get();
    }

    public function get_kasbon_by_id(string $karyawan_id)
    {
        return Kasbon::where("karyawan_id", $karyawan_id)
            ->where("status", "aktif")
            ->first();
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
            "jabatan_id" => $data["jabatan_id"],
            "divisi_id" => AuthDivisi::id()
        ]);

        $fields = [
            'id_fp',
            'no_ktp',
            'no_kk',
            'no_bpjs_kesehatan',
            'bank_id',
            'acc_no',
            'acc_name',
        ];

        foreach ($fields as $field) {
            if ($karyawan->$field !== null && $karyawan->$field !== '') {
                $this->simpanLog(
                    $karyawan->id,
                    "Mengisi {$field}"
                );
            }
        }

        return $karyawan;
    }

    private function simpanLog(string $karyawanId, string $deskripsi): void
    {
        LogKaryawan::create([
            'karyawan_id' => $karyawanId,
            'deskripsi'   => $deskripsi,
            'created_by' => Auth::id() ?? Auth::user()->id
        ]);
    }

    public function get_data_by_id(string $id)
    {
        return Karyawan::where("id", $id)->first();
    }

    public function get_log_karyawan(string $id)
    {
        return LogKaryawan::where("karyawan_id", $id)
            ->orderBy("created_at", "DESC")->get();
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

        $fields = [
            'id_fp',
            'no_ktp',
            'no_kk',
            'no_bpjs_kesehatan',
            'bank_id',
            'acc_no',
            'acc_name',
        ];

        foreach ($fields as $field) {
            $lama = $before[$field] ?? null;
            $baru = $karyawan->$field;

            if ($lama !== $baru) {
                $this->simpanLog(
                    $karyawan->id,
                    "Mengisi {$field}"
                );
            }
        }

        return $karyawan;
    }

    public function delete_by_id(string $id): void
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->delete();
    }
}
