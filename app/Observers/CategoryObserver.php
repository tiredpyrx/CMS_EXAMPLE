<?php

namespace App\Observers;

use App\Models\Category;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        $fieldRecords = [['user_id' => auth()->id() || 1, 'category_id' => $category->id, 'required' => true, 'label' => 'Başlık', 'handler' => 'title']];

        if ($category->have_details) $fieldRecords[] = ['user_id' => auth()->id() || 1, 'category_id' => $category->id, 'required' => true, 'label' => 'Slug', 'handler' => 'slug'];

        $category->fields()->createMany($fieldRecords);
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        $slugField = $category->fields()->where('handler', 'slug');

        if (!$category->have_details && $slugField->exists())
            $slugField->delete();

        if ($category->have_details && $slugField->doesntExist())
            $category->fields()->create(['user_id' => auth()->id() || 1, 'category_id' => $category->id, 'label' => 'Slug', 'handler' => 'slug']);
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "restore" event.
     */
    public function restored(Category $category): void
    {
        $category->posts()->onlyTrashed()->get()->each(fn($post) => $post->restore());
        $category->fields()->onlyTrashed()->get()->each(fn($field) => $field->restore());
    }

    /**
     * Handle the Category "force delete" event.
     */
    public function forceDeleted(Category $category): void
    {
        $category->posts()->get()->each(fn($post) => $post->forceDelete());
        $category->fields()->get()->each(fn($field) => $field->forceDelete());
    }
}
