<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        return view("pages.authentication.login");
    }

    public function post_login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

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
