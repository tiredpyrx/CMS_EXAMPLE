<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Field;
use App\Services\FieldService;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {

        $fieldRecords = [
            [
                'user_id' => auth()->id(),
                'category_id' => $category->id,
                'required' => true,
                'label' => 'Başlık',
                'handler' => 'title'
            ]
        ];

        $detailedFieldRecords = Field::DETAILED_RECORDS;

        foreach ($detailedFieldRecords as &$detailedRecord) {
            $detailedRecord['user_id'] = auth()->id();
            $detailedRecord['category_id'] = $category->id;
        }

        if ($category->have_details) {
            foreach ($detailedFieldRecords as &$detailedRecord) {
                $fieldRecords[] = $detailedRecord;
            }
        }

        $category->fields()->createMany($fieldRecords);

        $category->updateQuietly([
            'order' => Category::count() + 1
        ]);
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        $detailedFields = $category->fields()->whereIn('handler', Field::DETAILED_HANDLERS);

        if (!$category->have_details && $detailedFields->count())
            $detailedFields->each(fn ($field) => $field->delete());

        $detailedFieldRecords = (new FieldService())->getDetailedArray(auth()->id(), 'category_id', $category->id);

        if ($category->have_details && !$detailedFields->count())
            $category->fields()->createMany($detailedFieldRecords);
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
        $category->posts()->onlyTrashed()->get()->each(fn ($post) => $post->restore());
        $category->fields()->onlyTrashed()->get()->each(fn ($field) => $field->restore());
    }

    /**
     * Handle the Category "force delete" event.
     */
    public function forceDeleted(Category $category): void
    {
        $category->posts()->get()->each(fn ($post) => $post->forceDelete());
        $category->fields()->get()->each(fn ($field) => $field->forceDelete());
    }
}
