<?php

namespace App\Http\Repositories;

use App\Models\Kasbon;
use App\Models\KasbonTransaksi;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KasbonRepository
{
    public function get_all_data(array $filter = [])
    {
        return Kasbon::with([
            "karyawan",
            "karyawan.jabatan"
        ])
            ->when($filter['status'] ?? null, function ($q, $status) {
                $q->where('status', $status);
            })
            ->orderBy("status", "ASC")
            ->orderBy("tanggal_mulai", "DESC")
            ->get();
    }

    public function insert_data(array $data)
    {
        return DB::transaction(function () use ($data) {
            $exists = Kasbon::where('karyawan_id', $data['karyawan_id'])
                ->where('status', 'aktif')
                ->exists();

            if ($exists) {
                throw new Exception('Karyawan masih memiliki kasbon aktif');
            }

            $kasbon = Kasbon::create([
                'id'            => Str::uuid(),
                'karyawan_id'   => $data['karyawan_id'],
                'jumlah_awal'   => $data['jumlah'],
                'sisa'          => $data['jumlah'],
                'tanggal_mulai' => $data['tanggal_mulai'],
                'keterangan'    => $data['keterangan'] ?? null,
                'created_by'    => Auth::user()->id
            ]);

            KasbonTransaksi::create([
                'id'         => Str::uuid(),
                'kasbon_id'  => $kasbon->id,
                'tipe'       => 'topup',
                'nominal'    => $data['jumlah'],
                'tanggal'    => $data['tanggal_mulai'],
                'created_by' => Auth::user()->id
            ]);

            return $kasbon;
        });
    }

    public function get_data_by_id(string $id)
    {
        return Kasbon::where("id", $id)->first();
    }

    public function update_by_id(string $id, array $data)
    {
        $kasbon = Kasbon::findOrFail($id);

        $kasbon->update([
            "keterangan" => $data["keterangan"]
        ]);

        return $kasbon;
    }

    public function create(array $data): KasbonTransaksi
    {
        return KasbonTransaksi::create($data);
    }

    public function incrementSisa(Kasbon $kasbon, float $nominal): void
    {
        $kasbon->increment('sisa', $nominal);
    }

    public function decrementSisa(Kasbon $kasbon, float $nominal): void
    {
        $kasbon->decrement('sisa', $nominal);
    }

    public function updateStatus(Kasbon $kasbon, string $status): void
    {
        $kasbon->update([
            'status' => $status
        ]);
    }

    public function get_detail_data_by_id(string $id): Kasbon
    {
        return Kasbon::with([
            'karyawan.jabatan',
            'transaksi' => fn ($q) => $q->orderBy('tanggal', 'desc')
        ])->findOrFail($id);
    }

    public function delete_by_id(string $id): void
    {
        $kasbon = Kasbon::findOrFail($id);
        $kasbon->delete();
    }
}
