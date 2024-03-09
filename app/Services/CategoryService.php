<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryService
{
    public function create(array $filtered)
    {
        $additional = ['user_id' => auth()->id()];
        $merged = array_merge($filtered, $additional);
        return Category::create($merged);
    }

    public function update(Category $category, array $updated)
    {
        return $category->update($updated);
    }
}
