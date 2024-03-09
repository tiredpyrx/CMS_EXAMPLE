<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function __invoke(Request $request) {
        $safeRequest = $request->only('email', 'password');
        $remember = $request->input('remember', false);
        $log = Auth::attempt($safeRequest, $remember);
        if (!$log)
            return back()->withInput()->with('error', 'Hesap bulunamadı!');

        return to_route('dashboard')->with('success', 'Başarıyla giriş yapıldı!');
    }
}
