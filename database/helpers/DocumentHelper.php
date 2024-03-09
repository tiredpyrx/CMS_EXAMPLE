<?php

use Illuminate\Database\Eloquent\Model;
use Pest\Support\Str;

/**
 * @param string $fname
 * @return Model
*/
function getModel(string $fname)
{
    $fname = ucfirst($fname[0] . substr($fname, 1, strlen($fname)));
    $modelClass = "App\Models\\{$fname}";
    if (class_exists($modelClass))
        return app($modelClass);

    throw new BadFunctionCallException('Model class cannot be founded!');
}

function getRelationsCount(Model $model, string $foreign, string $primary)
{
    $foreign = getModel($foreign);
    return $foreign::where($primary, $model->id)->get()->count();
}

function shortenText($text, $length = 150)
{
    if (strlen($text) > $length)
        return substr($text, 0, $length). '...';
    return $text;
}

function getAll(string $name)
{
    return getModel($name)->all();
}