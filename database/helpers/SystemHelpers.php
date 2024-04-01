<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

function getModelClass(string $modelName): Model
{
    $modelName = ucfirst($modelName[0] . substr($modelName, 1, strlen($modelName)));
    $modelClass = "App\Models\\{$modelName}";
    if (class_exists($modelClass))
        return app($modelClass);

    throw new BadFunctionCallException("Model class cannot be founded for model name $modelName!");
}

function modelGetAll(string $modelName)
{
    return getModelClass($modelName)::all();
}

function getRelationsCount(Model $model, string $foreignModelName, string $primary)
{
    $foreign = getModelClass($foreignModelName);
    return $foreign::where($primary, $model->id)->get()->count();
}

function getTypeUpper(mixed $variable): string
{
    return strtoupper(gettype($variable));
}

function isType(mixed $variable, string $type): bool
{
    return getTypeUpper($variable) === strtoupper($type);
}
