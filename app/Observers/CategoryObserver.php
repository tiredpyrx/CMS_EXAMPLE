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
        $fieldRecords = [['user_id' => auth()->id() || 1, 'category_id' => $category->id, 'label' => 'Başlık', 'handler' => 'title']];

        if ($category->have_details) $fieldRecords[] = ['user_id' => auth()->id() || 1, 'category_id' => $category->id, 'label' => 'Slug', 'handler' => 'slug'];

        $category->fields()->createMany($fieldRecords);
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        //
    }
}
