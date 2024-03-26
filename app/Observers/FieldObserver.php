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

        if ($field->handler === 'changefreq') {
            $changeFrequencyOptionsRecords = [];
            foreach (Post::getChangeFrequencyValues() as $frequency) {
                $changeFrequencyOptionsRecords[] = [
                    'user_id' => $field->user_id,
                    'field_id' => $field->id,
                    'label' => ucfirst($frequency),
                    'value' => $frequency,
                    'as_option' => 1
                ];
            }

            $field->fields()->createMany($changeFrequencyOptionsRecords);
            return;
        }

        collect($field->category->posts)->each(function ($post) use ($field) {
            $pField = $field->replicate(['category_id']);
            $post->fields()->save($pField);
        });
    }

    public function updated(Field $field): void
    {
        if (!$field->category_id || $field->wasRecentlyCreated) return;

        Category::find($field->category_id)->posts()->each(function ($post) {
            $activity = Activity::all()->last();
            if (isset($activity->changes['old']))
                foreach ($activity->changes['old'] as $key => $oldValue) {
                    foreach (Field::where('post_id', $post->id) as $field) {
                        $newAttrs = $activity->changes['attributes'];
                        if ($field->getAttribute($key) == $oldValue) {
                            $field->update([$key => $newAttrs[$key]]);
                            dd($key, $newAttrs[$key]);
                        }
                    }
                }
        });
    }

    public function deleted(Field $field): void
    {
        collect($field->category->posts)->each(
            fn ($post) => $post->fields()
                ->where('handler', $field->handler)
                ->first()
                ->delete()
        );
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
