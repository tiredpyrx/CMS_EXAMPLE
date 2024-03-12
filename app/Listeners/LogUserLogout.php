<?php

namespace App\Listeners;

use Jenssegers\Agent\Agent;

class LogUserLogout
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(): void
    {
        $agent = new Agent();

        activity()
            ->causedBy(request()->user)
            ->withProperties([
                'logged_out_at' => now()->format('d/m/Y H:i:s'),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'browser' => $agent->browser(),
                'browser_version' => $agent->version($agent->browser()),
                'platform' => $agent->platform(),
                'platform_version' => $agent->version($agent->platform()),
                'device' => $agent->device(),
                'device_version' => $agent->platform($agent->device()),
            ])
            ->log(':causer.nickname logged out!');
    }
}
