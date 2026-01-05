<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class AuthDivisi
{
    public static function id(): ?string
    {
        return Auth::user()?->one_divisi_roles?->divisi?->id;
    }

    public static function check_data(): ?string
    {
        return Auth::user()?->one_divisi_roles;
    }
}
