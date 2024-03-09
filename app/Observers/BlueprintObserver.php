<?php

namespace App\Observers;

use App\Models\Blueprint;

class BlueprintObserver
{
    /**
     * Handle the Blueprint "created" event.
     */
    public function created(Blueprint $blueprint): void
    {
        //
    }

    /**
     * Handle the Blueprint "updated" event.
     */
    public function updated(Blueprint $blueprint): void
    {
        //
    }

    /**
     * Handle the Blueprint "deleted" event.
     */
    public function deleted(Blueprint $blueprint): void
    {
        //
    }

    /**
     * Handle the Blueprint "restored" event.
     */
    public function restored(Blueprint $blueprint): void
    {
        //
    }

    /**
     * Handle the Blueprint "force deleted" event.
     */
    public function forceDeleted(Blueprint $blueprint): void
    {
        //
    }
}
