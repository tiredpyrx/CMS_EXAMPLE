<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Field;
use App\Models\Post;
use App\Services\FieldService;
use Spatie\Activitylog\Models\Activity;

class FieldObserver
{
    private $service;

    public function __construct()
    {
        $this->service = (new FieldService());
    }

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
            $file = $field->firstFile();
            if ($field->type === 'image' && $file) {
                $fileReplicate = $file->replicate(['field_id']);
                $fileReplicate->updateQuietly(['field_id' => $pField->id]);
                $pField->files()->save($fileReplicate);
            } else if ($field->type === 'images') {
                foreach ($field->files as $file) {
                    $fileReplicate = $file->replicate(['field_id']);
                    $fileReplicate->updateQuietly(['field_id' => $pField->id]);
                    $pField->files()->save($fileReplicate);
                }
            }
        });
    }

    public function updated(Field $field): void
    {
        if (!$field->category_id || $field->wasRecentlyCreated) return;

        if (!$field->url && $field->prefix == url('')) {
            $field->updateQuietly(['prefix' => null]);
            foreach ($field->category->posts as $post) {
                $pField = $post->fields()->where('handler', $field->handler)->first();
                $pField->updateQuietly(['prefix' => null]);;
            }
        } else if ($field->url && $field->prefix != url('')) {
            $field->updateQuietly(['prefix' => url('')]);
            foreach ($field->category->posts as $post) {
                $pField = $post->fields()->where('handler', $field->handler)->first();
                $pField->updateQuietly(['prefix' => url('')]);
            }
        }

        if ($field->type === 'images')
            $this->service->syncImages($field);
    }

    public function deleted(Field $field): void
    {
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
