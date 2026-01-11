<?php

namespace App\Http\Services;

use App\Http\Repositories\ProfilRepository;
use Illuminate\Support\Facades\DB;

class ProfilService
{
    public function __construct(
        protected ProfilRepository $profil_repository
    ) {}

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->profil_repository->get_data_by_auth($id);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->profil_repository->update_by_auth($id, $data);
        });
    }

    public function ubah_password(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->profil_repository->change_password($id, $data);
        });
    }
}
