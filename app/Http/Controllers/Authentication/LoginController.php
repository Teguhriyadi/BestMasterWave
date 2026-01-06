<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        return view("pages.authentication.login");
    }

    public function post_login(LoginRequest $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        $cek = User::where("username", $request->username)->first();

        if (empty($cek)) return back()->with("error", "Akun Tidak Ditemukan");

        if ($cek["is_active"] != "1") return back()->with("error", "Akun Anda Tidak Aktif");

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            return redirect()
                ->intended('/admin-panel/dashboard')
                ->with('success', 'Anda Berhasil Login');
        }

        return back()
            ->withInput($request->only('username'))
            ->with('error', 'Username / Password Salah');
    }
}
