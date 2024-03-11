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

    public function destroy(Category $category)
    {
        foreach ($category->posts as $post) {
            foreach ($post->fields as $field) {
                $field->delete();
            }
            $post->delete();
        }
        foreach ($category->fields as $field) {
            $field->delete();
        }
        return $category->delete();
    }

    public function deleteMany(array $ids) {
        $IDontKnowWhatToNameThisArray = [];
        foreach ($ids as $id) {
            $res = $this->destroy(Category::find($id));
            array_push($IDontKnowWhatToNameThisArray, $res);
        }
        if (array_intersect($IDontKnowWhatToNameThisArray, [false]))
            return 0;
        return 1;
    }

    public function deleteAllSelected(array $ids)
    {
        return $this->deleteMany($ids);
    }

}
