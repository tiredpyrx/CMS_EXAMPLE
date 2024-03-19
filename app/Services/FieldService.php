<?php

namespace App\Services;

use App\Actions\GetUpdatedDatas;
use App\Models\Field;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

class FieldService
{
    public function create(array $filtered, Model $model): Field
    {
        $additional = ['user_id' => auth()->id(), strtolower(class_basename($model)) . '_id' => $model->id];
        $merged = array_merge($filtered, $additional);
        if ($filtered['type'] === 'select')
            $filtered['as_page'] = true;

        return Field::create($merged);
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
        $updated = (new GetUpdatedDatas())->execute($safeRequest, 'field', $field->id);
        $posts = Post::where('category_id', $field->category_id)->get();
        $actionsDummy = [];
        foreach ($posts as $post) {
            foreach ($post->fields as $pField) {
                foreach ($updated as $key => $value) {
                    $pFieldDoesntHaveValueYet = !$pField->getAttribute('value');
                    $keyIsValueAttribute = ($key === 'value');
                    $pFieldDataIsEqualToOriginalFieldsData = $pField->getAttribute($key) === $field->getAttribute($key);
                    $pFieldDoesntAlreadyHaveThisKeyValue = $pField->getAttribute($key) !== $value;
                    if ($pFieldDataIsEqualToOriginalFieldsData && $pFieldDoesntAlreadyHaveThisKeyValue) {
                        if ($keyIsValueAttribute) {
                            if ($pFieldDoesntHaveValueYet)
                                $actionsDummy[] = $pField->update(['value' => $value]);
                        } else
                            $actionsDummy[] = $pField->update([$key => $value]);
                    }
                }
            }
        }
        $actionsDummy[] = $field->update($safeRequest);
        $actions = [];
        foreach ($actionsDummy as $key => $value) {
            $actions[++$key] = $value;
        }
        return !array_search(false, $actions);
    }

    public function isFieldHandlerAlreadyExistsOnParentCategory($safeRequest, $category_id)
    {
        //
    }
}
