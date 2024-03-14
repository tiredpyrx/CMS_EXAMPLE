<?php

namespace App\Services;

use App\Models\Field;
use Illuminate\Database\Eloquent\Model;

class FieldService
{
    public function create(array $filtered, Model $model): Field
    {
        $additional = ['user_id' => auth()->id(), strtolower(class_basename($model)) . '_id' => $model->id];
        $merged = array_merge($filtered, $additional);

        return Field::create($merged);
    }

    public function getDetailedArray(int $user_id, string $parent_key, int $parent_id): array
    {
        $records = Field::HAVE_DETAILS_RECORDS;
        foreach ($records as &$record) {
            $record['user_id'] = $user_id;
            $record[$parent_key] = $parent_id;
        };

        return $records;
    }
}
