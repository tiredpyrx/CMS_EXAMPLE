<?php

namespace App\Services;

use App\Actions\GetUpdatedDatas;
use App\Actions\SaveUploadedFileToPublicDir;
use App\Enums\FieldTypes;
use App\Models\Category;
use App\Models\Field;
use App\Models\File;
use App\Models\Post;
use App\Observers\FieldObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class FieldService
{
    public function create(array $safeRequest, Model $model): Field
    {
        $this->validate($safeRequest, $model->id);

        $additional = ['user_id' => auth()->id(), 'category_id' => $model->id];
        $merged = array_merge($safeRequest, $additional);
        if ($safeRequest['type'] === 'select')
            $safeRequest['as_option'] = true;

        $field = Field::create($merged);

        if ($safeRequest['type'] === 'image')
            $this->tryToUploadImage($safeRequest, $field, $model->id);

        (new FieldObserver())->customCreated($field);

        return $field;
    }

    public function getDetailedArray(int $user_id, string $parent_key, int $parent_id): array
    {
        $records = Field::DETAILED_RECORDS;
        foreach ($records as &$record) {
            $record['user_id'] = $user_id;
            $record[$parent_key] = $parent_id;
        };

        return $records;
    }

    public function updateFields(Field $field, array $safeRequest): bool
    {
        $this->validate($safeRequest, $field->category_id, $field);

        $isImageExists = $this->validateImage($safeRequest);
        if ($isImageExists)
            $this->tryToUploadImage($safeRequest, $field, $field->category_id);

        $updated = (new GetUpdatedDatas())->execute($safeRequest, 'field', $field->id);
        $posts = Post::where('category_id', $field->category_id)->get();
        $actionsDummy = [];

        foreach ($posts as $post) {
            $pField = $post->fields()->where('handler', $safeRequest['handler'])->first();
            foreach ($safeRequest as $key => $value) {
                $keyIsTypeAttribute = ($key === 'type');
                $pFieldDoesntHaveValueYet = !$pField->getAttribute('value');
                $keyIsValueAttribute = ($key === 'value');
                if ($keyIsValueAttribute) {
                    if ($pFieldDoesntHaveValueYet)
                        $actionsDummy[] = $pField->update(['value' => $value]);
                } else if ($keyIsTypeAttribute) {
                    $featuresFieldCannotHave = array_diff(FieldTypes::getFeaturesForType($pField->type), FieldTypes::getFeaturesForType($value));
                    foreach ($featuresFieldCannotHave as $feature) {
                        $actionsDummy[] = $field->update([$feature => null]);
                        $actionsDummy[] = $pField->update([$feature => null]);
                    }
                    if (in_array($pField->type, Field::TYPES_WITH_CHILDREN)) {
                        $pField->fields()->each(fn ($f) => ($f->forceDelete()));
                        $field->fields()->each(fn ($pf) => ($pf->forceDelete()));
                    }
                    $actionsDummy[] = $pField->update(['type' => $value]);
                } else {
                    $pField->update([$key => $value]);
                }
            }
        }
        if (isset($featuresFieldCannotHave))
            $safeRequest = collect($safeRequest)->except($featuresFieldCannotHave)->toArray();

        $actionsDummy[] = $field->update($safeRequest);
        $actions = [];
        foreach ($actionsDummy as $key => $value) {
            $actions[++$key] = $value;
        }
        return !array_search(false, $actions);
    }

    public function validate(array $safeRequest, int $category_id, ?Field $field = new Field)
    {
        $this->validateHandleruUiqueness($safeRequest, $category_id, $field);
        return 1;
    }

    public function validateHandleruUiqueness(array $safeRequest, int $category_id, Field $field = new Field)
    {
        $isFieldHandlerAlreadyExistsOnParentCategory = Category::find($category_id)
            ->fields()
            ->where('handler', $safeRequest['handler'])
            ->exists();

        if ($isFieldHandlerAlreadyExistsOnParentCategory && $field->handler != $safeRequest['handler']) {
            session()->flash('error', 'Kategorinin alanlarında ' . $safeRequest['handler'] . ' işeyicisine sahip bir alan var!');
            throw ValidationException::withMessages([])
                ->redirectTo(
                    back()->getTargetUrl()
                );
        }
    }

    public function validateImage(array $safeRequest)
    {
        if (!isset($safeRequest['image'])) return null;

        // validation of mimetypes

        return 1;
    }

    public function tryToUploadImage(array $safeRequest, Field $field, int $categoryId)
    {
        $image = $safeRequest['image'];
        $imagePath = $this->getImageDirPath();
        $imageSource = (new SaveUploadedFileToPublicDir())->execute($image, $imagePath);
        $file = $field->files()->create([
            'user_id' => auth()->id(),
            'category_id' => $categoryId,
            'title' => isset($safeRequest['image_title']) ? $safeRequest['image_title'] : '',
            'description' => isset($safeRequest['image_description']) ? $safeRequest['image_description'] : '',
            'source' => $imageSource,
            'handler' => $field->handler,
        ]);
        return $file;
    }

    public function getImageDirPath()
    {
        return 'assets/fields/images/';
    }
}
