<?php

use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;

function shortenText($text, $length = 150)
{
    if (strlen($text) > $length)
        return substr($text, 0, $length) . '...';
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

function findUserWithNickname(string $nickname): User
{
    return User::where('nickname', $nickname)->firstOr("*", function () {
        throw new BadFunctionCallException('User cannot be founded!');
    });
}

function getCategory(string $categoryTitle)
{
    return Category::where('title', $categoryTitle)->firstOrFail();
}

function getCategoryPosts(string $categoryTitle)
{
    return Category::where('title', $categoryTitle)->firstOrFail()->posts;
}