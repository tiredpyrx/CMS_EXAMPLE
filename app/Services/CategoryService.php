<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Field;
use App\Models\Post;
use Illuminate\Http\Request;
use Spatie\Activitylog\Facades\LogBatch;

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
        LogBatch::startBatch();
        foreach (Post::where('category_id', $category->id) as $post) {
            foreach ($post->fields as $field) {
                $field->delete();
            }
            $post->delete();
        }
        foreach (Field::where('category_id', $category->id) as $field) {
            $field->delete();
        }
        $success = $category->delete();
        LogBatch::endBatch();
        return $success;
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
