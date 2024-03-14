<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Jenssegers\Agent\Agent;


class LogUserLogin
{
    public function handle(Login $event): void
    {

        $agent = new Agent();

        activity()
            ->causedBy(request()->user)
            ->useLog('private')
            ->withProperties([
                'datetime' => now()->format('d/m/Y H:i:s'),
                'causer' => $event->user,
                'ip' => request()->ip(),
                'user_agent' => $agent->getUserAgent(),
                'browser' => $agent->browser(),
                'browser_version' => $agent->version($agent->browser()),
                'platform' => $agent->platform(),
                'platform_version' => $agent->version($agent->platform()),
                'device' => $agent->device(),
                'device_version' => $agent->platform($agent->device()),
            ])
            ->log(':causer.nickname logged in!');
    }
}
