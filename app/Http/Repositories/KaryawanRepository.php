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
        if (empty(Auth::user()->one_divisi_roles)) {
            return Karyawan::orderBy("created_at", "DESC")->get();
        } else {
            return Karyawan::with(["divisi"])
                ->whereHas("divisi", function($q) {
                    $q->where("divisi_id", AuthDivisi::id());
                })
                ->orderBy("created_at", "DESC")->get();
        }
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

    private function compressImage($imageData, $maxFileSize = 1048576)
    {
        if (empty($imageData)) {
            throw new \Exception('Image data kosong');
        }

        $image = @imagecreatefromstring($imageData);

        if ($image === false) {
            throw new \Exception('Format gambar tidak dikenali');
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'img_') . '.jpg';

        $quality = 90;
        do {
            imagejpeg($image, $tempFile, $quality);
            $quality -= 5;
        } while (filesize($tempFile) > $maxFileSize && $quality > 10);

        $compressed = file_get_contents($tempFile);

        imagedestroy($image);
        unlink($tempFile);

        return $compressed;
    }

    public function insert_data(array $data)
    {
        $fileRepo = new FileRepo();
        $s3Url = null;

        if (!empty($data['foto'])) {

            $compressed = $this->compressImage($data['foto']);
            $s3Url = $fileRepo->saveFile($compressed, 'images/foto/');
        } elseif (!empty($data['foto'])) {

            $image = preg_replace(
                '#^data:image/\w+;base64,#i',
                '',
                $data['foto']
            );

            $image = str_replace(' ', '+', $image);
            $imageData = base64_decode($image, true);

            if ($imageData === false) {
                throw new \Exception('Base64 foto tidak valid');
            }

            $compressed = $this->compressImage($imageData);
            $s3Url = $fileRepo->saveFile($compressed, 'images/foto/');
        }

        $karyawan = Karyawan::create([
            "id_fp" => $data["id_sidik_jari"],
            "no_ktp" => empty($data["no_ktp"]) ? null : $data["no_ktp"],
            "no_kk" => empty($data["no_kk"]) ? null : $data["no_kk"],
            "no_bpjs_kesehatan" => empty($data["no_bpjs_kesehatan"]) ? null : $data["no_bpjs_kesehatan"],
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
            "bank_id" => empty($data["bank_id"]) ? null : $data["bank_id"],
            "acc_no" => empty($data["acc_no"]) ? null : $data["acc_no"],
            "acc_name" => empty($data["acc_name"]) ? null : $data["acc_name"],
            "created_by" => Auth::user()->id,
            "jabatan_id" => $data["jabatan_id"],
            "divisi_id" => AuthDivisi::id(),
            "foto" => empty($data["foto"]) ? null : $s3Url
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
        $fileRepo = new FileRepo();
        $s3Url = null;

        if (!empty($data['foto'])) {

            $compressed = $this->compressImage($data['foto']);
            $s3Url = $fileRepo->saveFile($compressed, 'images/foto/');
        } elseif (!empty($data['foto'])) {

            $image = preg_replace(
                '#^data:image/\w+;base64,#i',
                '',
                $data['foto']
            );

            $image = str_replace(' ', '+', $image);
            $imageData = base64_decode($image, true);

            if ($imageData === false) {
                throw new \Exception('Base64 foto tidak valid');
            }

            $compressed = $this->compressImage($imageData);
            $s3Url = $fileRepo->saveFile($compressed, 'images/foto/');
        }

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
            "jabatan_id" => $data["jabatan_id"],
            "foto" => empty($data["foto"]) ? null : $s3Url
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
