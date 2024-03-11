<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostService
{
    public function registerFields(Post $post, Request $request)
    {
        $category = Category::find($post->category_id);
        foreach ($category->fields as $field) {
            $fieldName = $field->handler;
            $fieldValue = $request->input($fieldName);
            $fieldDatas = [];
            $post->fields()->create([
                'user_id' => auth()->id(),
                'post_id' => $category->id,
                'label' => $field->label,
                'placeholder' => $field->placeholder,
                'handler' => $fieldName,
                'value' => $fieldValue,
                'column' => $field->placeholder,
                'type' => $field->type,
                'description' => $field->description
            ]);
        }
    }

    public function destroy(Post $post)
    {
        foreach ($post->fields as $field) {
            $field->delete();
        }
        return $post->delete();
    }

    public function deleteMany(array $ids)
    {
        $IDontKnowWhatToNameThisArray = [];
        foreach ($ids as $id) {
            $res = $this->destroy(Post::find($id));
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
