<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Field;
use App\Models\Post;
use App\Services\FieldService;
use Spatie\Activitylog\Models\Activity;

class FieldObserver
{
    public function customCreated(Field $field): void
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
            $pField = $post->fields()->save($pField);
            if ($field->type === 'image') {
                foreach ($field->files as $file) {
                    $pField->files()->save($file);
                }
            }
        });
    }

    public function updated(Field $field): void
    {
        if (!$field->category_id || $field->wasRecentlyCreated) return;
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

    public function tryToUploadImageAfterCreate(array $safeRequest, Field $field, int $modelId)
    {
        return (new FieldService())->tryToUploadImage($safeRequest, $field, $modelId);;
    }
}
