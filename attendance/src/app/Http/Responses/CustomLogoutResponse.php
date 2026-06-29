<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;

class CustomLogoutResponse implements LogoutResponseContract
{
    public function toResponse($request)
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/admin/login');
        }
        return redirect('/login');
    }
}