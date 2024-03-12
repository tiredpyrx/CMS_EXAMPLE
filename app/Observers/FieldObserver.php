<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Field;
use App\Models\Post;
use Spatie\Activitylog\Models\Activity;

class FieldObserver
{
    public function created(Field $field): void
    {
        if (!$field->category_id) return;
        collect($field->category->posts)->each(fn ($post) => $post->fields()->save($field));
    }

    public function updated(Field $field): void
    {
        if (!$field->category_id) return;

        Category::find($field->category_id)->posts()->each(function ($post) {
            $activity = Activity::all()->last();
            foreach ($activity->changes['old'] as $key => $oldValue) {
                foreach (Field::where('post_id', $post->id) as $field) {
                    $newAttrs = $activity->changes['attributes'];
                    if ($field->getAttribute($key) == $oldValue)
                        $field->update([$key => $newAttrs[$key]]);
                }
            }
        });
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
