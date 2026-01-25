<?php

namespace App\Http\Services;

use App\Helpers\AuthDivisi;
use App\Http\Mapper\DivisiMapper;
use App\Http\Mapper\KaryawanMapper;
use App\Http\Repositories\DivisiRepository;
use App\Http\Repositories\KaryawanRepository;
use App\Http\Requests\Karyawan\CreateRequest;
use App\Http\Requests\Karyawan\UpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KaryawanService
{
    public function __construct(
        protected KaryawanRepository $karyawan_repository,
        protected DivisiRepository $divisi_repository
    ) {}

    public function list()
    {
        $karyawan = $this->karyawan_repository->get_all_data();

        return KaryawanMapper::toTable($karyawan);
    }

    public function list_denda_karyawan(string $karyawan_id)
    {
        $karyawan = $this->karyawan_repository->get_denda_karyawan_by_id($karyawan_id);

        return KaryawanMapper::toDendaKaryawanById($karyawan);
    }

    public function list_pelanggaran_karyawan(string $karyawan_id)
    {
        $karyawan = $this->karyawan_repository->get_pelanggaran_karyawan_by_id($karyawan_id);

        return KaryawanMapper::toPelanggaranKaryawanById($karyawan);
    }

    public function detail_karyawan(string $karyawan_id)
    {
        return DB::transaction(function () use ($karyawan_id) {
            return $this->karyawan_repository->get_kasbon_by_id($karyawan_id);
        });
    }

    public function list_karyawan()
    {
        $karyawan = $this->karyawan_repository->get_list_karyawan();

        return KaryawanMapper::toListKaryawan($karyawan);
    }

    public function create(CreateRequest $request)
    {
        return DB::transaction(function () use ($request) {

            $data = $request->validated();

            if ($request->hasFile('foto')) {

                $file = $request->file('foto');

                if (!str_starts_with($file->getMimeType(), 'image/')) {
                    throw new \Exception('File foto harus berupa gambar');
                }

                $data['foto'] = file_get_contents(
                    $file->getRealPath()
                );
            } elseif ($request->filled('foto')) {
                $data['foto'] = $request->foto;
            }

            $data['created_by'] = Auth::id();
            $data['divisi_id']  = AuthDivisi::id();

            return $this->karyawan_repository->insert_data($data);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function () use ($id) {
            return $this->karyawan_repository->get_data_by_id($id);
        });
    }

    public function show_log(string $id)
    {
        return DB::transaction(function () use ($id) {
            $log = $this->karyawan_repository->get_log_karyawan($id);

            return KaryawanMapper::toListLogKaryawan($log);
        });
    }

    public function update(string $id, UpdateRequest $request)
    {
        return DB::transaction(function () use ($id, $request) {
            $data = $request->validated();

            if ($request->hasFile('foto')) {

                $file = $request->file('foto');

                if (!str_starts_with($file->getMimeType(), 'image/')) {
                    throw new \Exception('File foto harus berupa gambar');
                }

                $data['foto'] = file_get_contents(
                    $file->getRealPath()
                );
            } elseif ($request->filled('foto')) {
                $data['foto'] = $request->foto;
            }

            $data['created_by'] = Auth::id();
            $data['divisi_id']  = AuthDivisi::id();

            return $this->karyawan_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->karyawan_repository->delete_by_id($id);
        });
    }

    public function getRolesByDivision(string $divisionId)
    {
        return $this->divisi_repository->getRolesByDivisi($divisionId);
    }
}
