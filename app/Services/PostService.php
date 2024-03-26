<?php

namespace App\Services;

use App\Actions\FilterRequest;
use App\Actions\GetUpdatedDatas;
use App\Models\Category;
use App\Models\Field;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PostService
{
    public function registerFields(Post $post, Request $request)
    {
        $category = Category::find($post->category_id);
        foreach ($category->fields as $field) {
            $fieldName = $field->handler;
            $fieldValue = $request->input($fieldName);
            switch ($field->type) {
                case 'multifield':
                    $newField = $post->fields()->create([
                        'user_id' => auth()->id(),
                        'post_id' => $post->id,
                        'label' => $field->label,
                        'placeholder' => $field->placeholder,
                        'handler' => $fieldName,
                        'column' => $field->column,
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
                        'column' => $field->column,
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
                case 'image':
                    // File
                    break;
                default:
                    $post->fields()->create([
                        'user_id' => auth()->id(),
                        'post_id' => $post->id,
                        'label' => $field->label,
                        'placeholder' => $field->placeholder,
                        'handler' => $fieldName,
                        'column' => $field->column,
                        'type' => $field->type,
                        'value' => $fieldValue,
                        'min_value' => $field->min_value,
                        'max_value' => $field->max_value,
                        'prefix' => $field->prefix,
                        'suffix' => $field->suffix,
                        'step' => $field->step,
                        'as_option' => $field->as_option,
                        'description' => $field->description,
                        'required' => $field->required,
                        'active' => $field->active
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
        $result = [];
        foreach ($ids as $id) {
            $res = $this->destroy(Post::find($id));
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

    public function create(Request $request, Category $category)
    {
        $this->validateRequiredFields($request, $category);
        return $post = Post::create([
            'title' => $request->input('title'),
            'user_id' => auth()->id(),
            'category_id' => $category->id,
            'active' => $request->has('active')
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $this->validateRequiredFields($request, $post);
        $safeRequest = $this->getSafeRequest($request);
        return $this->updateChangedDatas($safeRequest, $post);
    }

    // can go to sitemap.xml
    public function publish(Post $post): bool
    {
        return $post->update(['published', true]);
    }

    // cant go to sitemap.xml, if it is, will be removed
    public function unpublish(Post $post): bool
    {
        return $post->update(['published', false]);
    }


    /**
     * @var Category $category
     */
    public function handlePostsDetailedFieldsValidationOnStore(Request $request, array &$rules)
    {
        $category = $request->route()->parameter('category');
        if ($category->have_details)
            foreach (Field::DETAILED_HANDLERS as $prHandler) {
                $rules[$prHandler] = 'required';
            }
    }

    /**
     * @var Post $post
     */
    public function handlePostsDetailedFieldsValidationOnUpdate(Request $request, array &$rules)
    {
        $post = $request->route()->parameter('post');
        if ($post->getSlugAttribute())
            foreach (Field::DETAILED_HANDLERS as $prHandler) {
                $rules[$prHandler] = 'required';
            }
    }

    public function getSafeRequest(Request $request)
    {
        return (new FilterRequest())->execute($request, 'post');
    }

    public function updateChangedDatas(array $safeRequest, Post $post): bool
    {
        $updated = (new GetUpdatedDatas())->execute($safeRequest, 'post', $post->id);
        return $post->update($updated);
    }

    public function updateFields(Request $request, Post $post): bool
    {
        return $this->updateChangedFields($request, $post);
    }

    public function updateChangedFields(Request $request, Post $post): bool
    {
        $result = [];
        foreach ($post->fields as $field) {
            $fieldValue = $request->input($field->handler);
            if ($field->value != $fieldValue)
                $result[] = $field->update(['value' => $fieldValue]);
        }
        return !in_array(false, $result);
    }

    public function updateMultiField(Request $request, Post $post)
    {
        $post->fields()->where('type', 'multifield')->each(fn ($field) => $field->fields()->forceDelete());
        foreach ($post->fields()->where('type', 'multifield')->get() as $field) {
            $fValue = $request->input($field->handler);
            collect($fValue)->each(function ($value) use ($field, $post) {
                if (is_array($value)) $value = $value[0];
                $field->fields()->create([
                    'user_id' => auth()->id(),
                    'field_id' => $post->id,
                    'handler' => hexdec(uniqid($field->handler)),
                    'value' => $value,
                ]);
            });
        }
    }

    public function updateSiblingField(Request $request, Post $post)
    {
        $post->fields()->where('type', 'siblingfield')->each(fn ($field) => $field->fields()->forceDelete());
        foreach ($post->fields()->where('type', 'siblingfield')->get() as $field) {
            $fValue = $request->input($field->handler);
            collect($fValue)->each(function ($value) use ($field, $post) {
                if (is_array($value)) $value = $value[0];
                $field->fields()->create([
                    'user_id' => auth()->id(),
                    'field_id' => $post->id,
                    'handler' => hexdec(uniqid($field->handler)),
                    'value' => $value,
                ]);
            });
        }
    }

    public function validateRequiredFields(Request $request, Category|Post $parent)
    {
        foreach ($parent->fields as $field) {
            if ($field->required && is_null($request->input($field->handler))) {
                session()->flash('error', $field->label . ' alanı zorunludur!');
                throw ValidationException::withMessages([])->redirectTo(back()->getTargetUrl());
            }
        }
    }
}
