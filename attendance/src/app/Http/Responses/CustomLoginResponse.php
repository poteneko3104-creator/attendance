<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class CustomLoginResponse implements LoginResponseContract
{
    /**
     * ログイン成功時のリダイレクト先を制御
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/admin/attendance/list');
        }
        return redirect()->intended(config('fortify.home'));
    }
}
