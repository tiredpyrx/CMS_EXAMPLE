<?php

namespace App\Services;

use App\Models\Field;
use Illuminate\Database\Eloquent\Model;

class FieldService
{
    public function create(array $filtered, Model $model)
    {
        $additional = ['user_id' => auth()->id(), strtolower(class_basename($model)) . '_id' => $model->id];
        $merged = array_merge($filtered, $additional);
        return Field::create($merged);
    }
}
