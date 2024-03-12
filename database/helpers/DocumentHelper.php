<?php

use Illuminate\Database\Eloquent\Model;
use Pest\Support\Str;
use Carbon\Carbon;

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

function getAll(string $name)
{
    return getModel($name)::all();
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

function limitNumber(int|string $num, int $max = 10): string
{
    if ($num > $max) return "$max+";
    return $num;
}

function humanDate(Carbon $date)
{
    return $date->diffForHumans();
}