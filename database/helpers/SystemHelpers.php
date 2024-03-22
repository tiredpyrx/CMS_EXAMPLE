<?php

use Illuminate\Database\Eloquent\Model;

function getModelClass(string $modelClassName)
{
    $modelClassName = ucfirst($modelClassName[0] . substr($modelClassName, 1, strlen($modelClassName)));
    $modelClass = "App\Models\\{$modelClassName}";
    if (class_exists($modelClass))
        return app($modelClass);

    throw new BadFunctionCallException('Model class cannot be founded!');
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
