<?php

namespace App\Http\Repositories;

use App\Helpers\AuthDivisi;
use App\Models\Divisi;
use App\Models\DivisiRole;
use App\Models\UserDivisiRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DivisiRoleRepository
{
    public function get_all_data()
    {
        if (empty(Auth::user()->one_divisi_roles)) {
            return Divisi::with("roles")
            ->orderBy("nama_divisi")
            ->get();
        } else {
            return Divisi::where("id", AuthDivisi::id())->with("roles")
            ->orderBy("nama_divisi")
            ->get();
        }
    }

    public function get_users_divisi_role()
    {
        return UserDivisiRole::where("divisi_id", AuthDivisi::id())
            ->get();
    }

    public function get_akses_role()
    {
        return DivisiRole::where("divisi_id", AuthDivisi::id())
            ->get();
    }

    public function insertRoles(string $divisionId, array $roleIds): void
    {
        DB::transaction(function () use ($divisionId, $roleIds) {

            DivisiRole::where('divisi_id', $divisionId)->delete();

            if (empty($roleIds)) {
                return;
            }

            $payload = [];

            foreach ($roleIds as $roleId) {
                $payload[] = [
                    'id'         => (string) Str::uuid(),
                    'divisi_id'  => $divisionId,
                    'role_id'    => $roleId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DivisiRole::insert($payload);
        });
    }

    public function syncRoles(int|string $divisionId, array $roleIds): void
    {
        $division = DivisiRole::findOrFail($divisionId);

        $division->roles()->sync($roleIds);
    }

    public function getRoleIdsByDivision(string $divisionId)
    {
        return Divisi::with('roles:id')
            ->findOrFail($divisionId)
            ->roles
            ->pluck('id');
    }
}
