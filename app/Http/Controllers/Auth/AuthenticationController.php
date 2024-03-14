<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

class AuthenticationController extends Controller
{
    public function __invoke(Request $request)
    {
        $safeRequest = $request->only('nickname', 'password');
        $remember = $request->input('remember', false);
        $log = Auth::attempt($safeRequest, $remember);

        // $user->notify('remember');

        $agent = new Agent();
        $nickname = $request->nickname;

        if (!$log) {
            activity()
            ->useLog('authentication_failed')
            ->withProperties([
                'nickname' => $nickname,
                'datetime' => now()->format('d/m/Y H:i:s'),
                'ip' => request()->ip(),
                'causer' => null,
                'user_agent' => $agent->getUserAgent(),
                'browser' => $agent->browser(),
                'browser_version' => $agent->version($agent->browser()),
                'platform' => $agent->platform(),
                'platform_version' => $agent->version($agent->platform()),
                'device' => $agent->device(),
                'device_version' => $agent->platform($agent->device()),
            ])
            ->log("Authentication failed with username {$nickname}!");

            return back()->withInput()->with('error', 'Hesap bulunamadı!');
        }

        return to_route('dashboard')->with('success', 'Başarıyla giriş yapıldı!');
    }
}
