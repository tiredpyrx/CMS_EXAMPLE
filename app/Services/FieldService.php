<?php

namespace App\Services;

use App\Actions\GetUpdatedDatas;
use App\Enums\FieldTypes;
use App\Models\Category;
use App\Models\Field;
use App\Models\Post;
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
        $this->validate($safeRequest, $field->category_id, $field);
        $updated = (new GetUpdatedDatas())->execute($safeRequest, 'field', $field->id);
        $posts = Post::where('category_id', $field->category_id)->get();
        $actionsDummy = [];

        foreach ($posts as $post) {
            $pField = $post->fields()->where('handler', $safeRequest['handler'])->first();
            foreach ($safeRequest as $key => $value) {
                $keyIsTypeAttribute = ($key === 'type');
                $pFieldDoesntHaveValueYet = !$pField->getAttribute('value');
                $keyIsValueAttribute = ($key === 'value');
                $pFieldDataIsEqualToOriginalFieldsData = $pField->getAttribute($key) === $field->getAttribute($key);
                $pFieldDoesntAlreadyHaveThisKeyValue = $pField->getAttribute($key) !== $value;
                // if ($pFieldDataIsEqualToOriginalFieldsData && $pFieldDoesntAlreadyHaveThisKeyValue) {

                // }
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

    public function validate($safeRequest, $category_id, $field = new Field)
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
        return 1;
    }
}
