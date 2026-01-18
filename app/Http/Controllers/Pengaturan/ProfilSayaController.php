<?php

namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfilSaya\UpdateRequest as ProfilSayaUpdateRequest;
use App\Http\Requests\UbahPassword\UpdateRequest;
use App\Http\Services\ProfilService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProfilSayaController extends Controller
{
    public function __construct(
        protected ProfilService $profil_service
    ) {}

    public function index()
    {
        try {
            $data["profil"] = $this->profil_service->get_data();

            return view("pages.modules.pengaturan.profil-saya.index", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function update(ProfilSayaUpdateRequest $request, $id)
    {
        try {
            $this->profil_service->update($id, $request->all());

            return back()->with('success', 'Data berhasil diperbarui');

        } catch (ModelNotFoundException $e) {

            return back()->with('error', 'Akun tidak ditemukan');

        } catch (\Throwable $e) {

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function ubah_password(UpdateRequest $request, $id)
    {
        try {
            $this->profil_service->ubah_password($id, $request->all());

            return back()->with('success', 'Password berhasil diperbarui');

        } catch (ModelNotFoundException $e) {

            return back()->with('error', 'Akun tidak ditemukan');

        } catch (\Throwable $e) {

            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
