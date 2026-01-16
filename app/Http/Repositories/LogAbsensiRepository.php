<?php

namespace App\Http\Repositories;

use App\Helpers\AuthDivisi;
use App\Models\Barang;
use App\Models\LogAbsensi;
use App\Models\Supplier;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class LogAbsensiRepository
{
    public function get_all_data(): Collection
    {
        $query = LogAbsensi::query()
            ->orderBy('tanggal_waktu', 'DESC');

        if (!empty(AuthDivisi::check_data())) {
            $query->where('divisi_id', AuthDivisi::id());
        }

        return $query->get()->map(function ($item) {

            $tanggal = $item->tanggal_waktu;
            $jamMenit = $tanggal->format('H:i');

            if ($jamMenit >= '17:00') {
                $status = 'Pulang';
            } elseif ($jamMenit <= '08:00') {
                $status = 'Tepat Waktu';
            } else {
                $status = 'Terlambat';
            }

            return [
                'id'             => $item->id,
                'divisi'         => $item->divisi->nama ?? '-',
                'nama_karyawan'  => $item->karyawan->nama ?? '-',
                'tanggal_waktu'  => $this->formatTanggalIndo($item->tanggal_waktu),
                'status'         => $status,
                'lokasi'         => $item->lokasi->nama_lokasi,
                'upload'         => $this->formatTanggalIndo($item->created_at),
                'modif'          => $this->formatTanggalIndo($item->updated_at),
            ];
        });
    }

    public function insert_data(array $data)
    {
        $supplier = Barang::create([
            "sku_barang" => $data["sku_barang"],
            "harga_modal" => $data["harga_modal"],
            "harga_pembelian_terakhir" => $data['harga_pembelian_terakhir'] ?? 0,
            "tanggal_pembelian_terakhir" => $data['tanggal_pembelian_terakhir'] ?? null,
            "status_sku" => "A",
            "seller_id" => $data["seller_id"] ?? null,
            "created_by" => Auth::user()->id,
            "divisi_id" => AuthDivisi::id()
        ]);

        return $supplier;
    }

    private function formatTanggalIndo($tanggal)
    {
        if (!$tanggal) {
            return '-';
        }

        $bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return
            $tanggal->format('d') . ' ' .
            $bulan[(int) $tanggal->format('m')] . ' ' .
            $tanggal->format('Y H:i:s');
    }

    public function get_data_by_id(string $id)
    {
        $item = LogAbsensi::where('id', $id)->first();

        if (!$item) {
            return null;
        }

        $tanggal = $item->tanggal_waktu;
        $jamMenit = $tanggal->format('H:i');

        if ($jamMenit >= '17:00') {
            $status = 'Pulang';
        } elseif ($jamMenit <= '08:00') {
            $status = 'Tepat Waktu';
        } else {
            $status = 'Terlambat';
        }

        return [
            'id'             => $item->id,
            'nama_karyawan'  => $item->karyawan->nama ?? '-',
            'tanggal_waktu'  => $tanggal->format('Y-m-d\TH:i'),
            'status'         => $status
        ];
    }


    public function update_by_id(string $id, array $data)
    {
        $log_absensi = LogAbsensi::findOrFail($id);

        $log_absensi->update([
            "tanggal_waktu" => $data["tanggal_waktu"],
            "updated_at" => now()
        ]);

        return $log_absensi;
    }

    public function delete_by_id(string $id): void
    {
        $log_absensi = LogAbsensi::findOrFail($id);
        $log_absensi->delete();
    }
}
