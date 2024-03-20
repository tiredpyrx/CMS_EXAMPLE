<?php

namespace App\Services;

use App\Actions\FilterRequest;
use App\Models\Category;
use App\Models\Field;
use App\Models\Post;
use Illuminate\Http\Request;
use Spatie\Activitylog\Facades\LogBatch;

class CategoryService
{
    public function create(Request $request)
    {
        $safeRequest = (new FilterRequest())->execute($request, 'category');
        $merged = $this->getMergedOnCreate($safeRequest);
        return Category::create($merged);
    }

    public function getMergedOnCreate(array $safeRequest)
    {
        $additional = ['user_id' => auth()->id()];
        return array_merge($safeRequest, $additional);
    }

    public function update(Category $category, array $updated)
    {
        return $category->update($updated);
    }

    public function destroy(Category $category)
    {
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
        return $success;
    }

    public function deleteMany(array $ids) {
        $result = [];
        foreach ($ids as $id) {
            $res = $this->destroy(Category::find($id));
            array_push($result, $res);
        }
        if (array_intersect($result, [false]))
            return 0;
        return 1;
    }

    public function deleteAllSelected(array $ids)
    {
        return $this->deleteMany($ids);
    }

}
