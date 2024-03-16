<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Post;
use Carbon\Carbon;
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
            switch ($field->type) {
                case 'multifield':
                    $newField = $post->fields()->create([
                        'user_id' => auth()->id(),
                        'post_id' => $post->id,
                        'label' => $field->label,
                        'placeholder' => $field->placeholder,
                        'handler' => $fieldName,
                        'column' => $field->placeholder,
                        'type' => $field->type,
                        'description' => $field->description
                    ]);
                    collect($fieldValue)->each(function ($value) use ($newField, $post) {
                        $newField->fields()->create([
                            'user_id' => auth()->id(),
                            'field_id' => $post->id,
                            'handler' => hexdec(uniqid($newField->handler)),
                            'value' => $value,
                        ]);
                    });
                    break;
                case 'siblingfield':
                    $newField = $post->fields()->create([
                        'user_id' => auth()->id(),
                        'post_id' => $post->id,
                        'label' => $field->label,
                        'placeholder' => $field->placeholder,
                        'handler' => $fieldName,
                        'column' => $field->placeholder,
                        'type' => $field->type,
                        'description' => $field->description
                    ]);
                    collect($fieldValue)->each(function ($value) use ($newField, $post) {
                        $newField->fields()->create([
                            'user_id' => auth()->id(),
                            'field_id' => $post->id,
                            'handler' => hexdec(uniqid($newField->handler)),
                            'value' => $value,
                        ]);
                    });
                    break;
                default:
                    $post->fields()->create([
                        'user_id' => auth()->id(),
                        'post_id' => $post->id,
                        'label' => $field->label,
                        'placeholder' => $field->placeholder,
                        'handler' => $fieldName,
                        'value' => $fieldValue,
                        'column' => $field->placeholder,
                        'type' => $field->type,
                        'description' => $field->description
                    ]);
                    break;
            }
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

    public function create(Request $request, array $safeRequest, Category $category)
    {
        $publishDate = $request->input('publish_date');
        $active = $request->has('active');
        $now = now();
        $additional = [
            'user_id' => auth()->id(),
            'category_id' => $category->id,
            'active' => $active
        ];
        if ($publishDate) {
            $publishDate = Carbon::parse($publishDate);
            $additional['publish_date'] = $publishDate;
            if ($publishDate <= $now) $additional['published'] = true;
            else $additional['published'] = false;
        } else {
            $additional['publish_date'] = $now;
            $additional['published'] = true;
        }
        $merged = array_merge($safeRequest, $additional);
        return $post = Post::create($merged);
    }

    // send it to sitemap.xml
    public function publish(Post $post): bool
    {
        return $post->update(['published', true]);
    }

    // remove from sitemap.xml
    public function unpublish(Post $post): bool
    {
        return $post->update(['published', false]);
    }
}
