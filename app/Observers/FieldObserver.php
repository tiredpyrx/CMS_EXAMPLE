<?php

namespace App\Observers;

use App\Models\Field;

class FieldObserver
{
    /**
     * Handle the Field "created" event.
     */
    public function created(Field $field): void
    {
        //
    }

    /**
     * Handle the Field "updated" event.
     */
    public function updated(Field $field): void
    {
        //
    }

    /**
     * Handle the Field "deleted" event.
     */
    public function deleted(Field $field): void
    {
        //
    }

    /**
     * Handle the Field "restored" event.
     */
    public function restored(Field $field): void
    {
        //
    }

    /**
     * Handle the Field "force deleted" event.
     */
    public function forceDeleted(Field $field): void
    {
        //
    }
}
