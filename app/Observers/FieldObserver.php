<?php

namespace App\Observers;

use App\Models\Field;

class FieldObserver
{
    public function created(Field $field): void
    {
        if (!$field->category_id) return;
        collect($field->category->posts)->each(fn ($post) => $post->fields()->save($field));
    }

    public function updated(Field $field): void
    {
        //
    }

    public function deleted(Field $field): void
    {
        //
    }

    public function restored(Field $field): void
    {
        //
    }

    public function forceDeleted(Field $field): void
    {
        //
    }
}
