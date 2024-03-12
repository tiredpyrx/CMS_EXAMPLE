<?php

namespace App\Pipes;

use Closure;
use Illuminate\Support\Arr;
use Spatie\Activitylog\Contracts\LoggablePipe;
use Spatie\Activitylog\EventLogBag;

class ActivityPreventAttributePipe implements LoggablePipe
{
    public function handle(EventLogBag $event, Closure $next): EventLogBag
    {
        Arr::forget($event->changes, ["attributes.updated_at", "old.updated_at"]);

        return $next($event);
    }
}
