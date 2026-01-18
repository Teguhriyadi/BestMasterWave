<?php

namespace App\Http\Services;

use App\Http\Mapper\KasbonMapper;
use App\Http\Repositories\KasbonRepository;
use App\Models\Kasbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KasbonService
{
    public function __construct(
        protected KasbonRepository $kasbon_repository
    ) {}

    public function list()
    {
        $supplier = $this->kasbon_repository->get_all_data();

        return KasbonMapper::toTable($supplier);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->kasbon_repository->insert_data($data);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->kasbon_repository->get_data_by_id($id);
        });
    }

    public function detail(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->kasbon_repository->get_detail_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->kasbon_repository->update_by_id($id, $data);
        });
    }

    public function topUp(Kasbon $kasbon, array $data): void
    {
        if ($kasbon->status !== 'aktif') {
            throw new Exception('Kasbon sudah lunas');
        }

        DB::transaction(function () use ($kasbon, $data) {

            $this->kasbon_repository->create([
                'id'         => Str::uuid(),
                'kasbon_id'  => $kasbon->id,
                'tipe'       => 'topup',
                'nominal'    => $data['nominal'],
                'tanggal'    => $data['tanggal'],
                'keterangan' => $data['keterangan'] ?? null,
                'created_by' => Auth::user()->id
            ]);

            $this->kasbon_repository->incrementSisa($kasbon, $data['nominal']);
        });
    }

    public function bayar(Kasbon $kasbon, array $data): void
    {
        if ($kasbon->status !== 'aktif') {
            throw new Exception('Kasbon sudah lunas');
        }

        DB::transaction(function () use ($kasbon, $data) {

            if ($data['nominal'] > $kasbon->sisa) {
                throw new Exception('Nominal pembayaran melebihi sisa kasbon');
            }

            $this->kasbon_repository->create([
                'id'         => Str::uuid(),
                'kasbon_id'  => $kasbon->id,
                'tipe'       => 'pembayaran',
                'nominal'    => $data['nominal'],
                'metode'     => $data['metode'],
                'tanggal'    => $data['tanggal'],
                'keterangan' => $data['keterangan'] ?? null,
                'created_by' => Auth::user()->id
            ]);

            $this->kasbon_repository->decrementSisa($kasbon, $data['nominal']);

            if ($kasbon->sisa == 0) {
                $this->kasbon_repository->updateStatus($kasbon, 'lunas');
            }
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->kasbon_repository->delete_by_id($id);
        });
    }
}
