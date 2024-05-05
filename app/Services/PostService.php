<?php

namespace App\Services;

use App\Actions\FilterRequest;
use App\Actions\GetUpdatedDatas;
use App\Actions\SaveUploadedFileToPublicDir;
use App\Models\Category;
use App\Models\Field;
use App\Models\Post;
use BadMethodCallException;
use Carbon\Carbon;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class PostService
{
    public function registerFields(Post $post, Request $request)
    {
        $category = Category::find($post->category_id);
        /** @var \App\Models\Field $field */
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
                            // ? why field_id is $post->id
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
                case 'select':
                    // option children
                    break;
                case 'image':
                    $uploadedImage = $request->file($field->handler);
                    $newField = $post->fields()->create([
                        'user_id' => auth()->id(),
                        'post_id' => $post->id,
                        'label' => $field->label,
                        'handler' => $fieldName,
                        'column' => $field->column,
                        'type' => $field->type,
                        'description' => $field->description,
                    ]);
                    $imageSource =
                        (new SaveUploadedFileToPublicDir())->execute(
                            $uploadedImage,
                            $this->getImagesDirPath()
                        );

                    $newField->files()->create([
                        'user_id' => auth()->id(),
                        'category_id' => $post->category_id,
                        'title' => $request->input('image_title'),
                        'description' => $request->input('image_description'),
                        'source' => $imageSource,
                        'handler' => $field->handler,
                    ]);
                    break;
                case 'images':
                    $uploadedImages = $request->allFiles();
                    $newField = $post->fields()->create([
                        'user_id' => auth()->id(),
                        'post_id' => $post->id,
                        'label' => $field->label,
                        'handler' => $fieldName,
                        'column' => $field->column,
                        'type' => $field->type,
                        'description' => $field->description,
                    ]);
                    $imageFileRecords = [];
                    foreach ($uploadedImages as $newImage) {
                        $imageFileRecords[] = [
                            'user_id' => auth()->id(),
                            'category_id' => $post->category_id,
                            'title' => $request->input('image_title'),
                            'description' => $request->input('image_description'),
                            'source' => (new SaveUploadedFileToPublicDir())->execute($newImage, $this->getImagesDirPath()),
                            'handler' => $field->handler,
                        ];
                    }
                    $newField->files()->createMany($imageFileRecords);
                    break;
                case 'video':
                    // File
                    break;
                case 'videos':
                    // Files
                    break;
                case 'file':
                    // File
                    break;
                case 'files':
                    // Files
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


        $this::tryToLogToSitemap();
    }

    public function destroy(Post $post)
    {
        foreach ($post->fields as $field) {
            $field->delete();
        }

        $success = $post->delete();
        $this::tryToLogToSitemap();

        return $success;
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
        $success = $this->deleteMany($ids);
        $this::tryToLogToSitemap();
        return $success;
    }

    public function create(Request $request, Category $category)
    {
        $this::validateFields($request, $category);
        return Post::create([
            'title' => $request->input('title'),
            'user_id' => auth()->id(),
            'category_id' => $category->id,
            'active' => $request->has('active')
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $this::validateFields($request, $post);
        $safeRequest = $this->getSafeRequest($request);
        $success = $this->updateChangedDatas($safeRequest, $post);
        return $success;
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
        $actions = [];
        $this->updateMultiField($request, $post);
        $this->updateSiblingField($request, $post);
        $this->updateImageFields($request, $post);
        return $this->updateChangedFields($request, $post);
    }

    public function updateImageFields(Request $request, Post $post)
    {
        $post->fields()->where('type', 'image')->each(function ($field) use ($request) {
            $oldImage = $field->firstFile();
            $oldImageSource = $oldImage?->source;
            $newImage = $request->file($field->handler);
            if ($newImage) {
                if ($oldImageSource && File::exists($oldImageSource))
                    File::delete($oldImageSource);
                $imageSource = (new SaveUploadedFileToPublicDir())->execute($newImage, $this->getImagesDirPath());
                if ($imageSource)
                    $oldImage->update(['source' => $imageSource]);
            }
        });
    }


    public function updateChangedFields(Request $request, Post $post): bool
    {
        $result = [];
        foreach ($post->fields as $field) {
            $fieldValue = $request->input($field->handler);
            if ($field->value != $fieldValue)
                $result[] = $field->update(['value' => $fieldValue]);
        }
        $this::tryToLogToSitemap();
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

    public static function validateFields(Request $request, Category|Post $parent)
    {
        PostService::validateRequiredFields($request, $parent);
        PostService::validateSluggableFeature($request, $parent);
        PostService::validateURLFeature($request, $parent);
    }

    private static function validateRequiredFields(Request $request, Category|Post $parent)
    {
        foreach ($parent->fields as $field) {
            if ($field->required && is_null($request->input($field->handler))) {
                session()->flash('error', $field->label . ' alanı zorunludur!');
                throw ValidationException::withMessages([])->redirectTo(back()->getTargetUrl());
            }
        }
    }

    private static function validateSluggableFeature(Request $request, Category|Post $parent)
    {
        foreach ($parent->fields as $field) {
            $isFieldValueNotFormattedAsSlug = Str::slug($request->input($field->handler)) != $request->input($field->handler);
            if ($field->sluggable && $isFieldValueNotFormattedAsSlug) {
                session()->flash('error', $field->label . ' alan değeri slug formatında olmak zorundadır!');
                throw ValidationException::withMessages([])->redirectTo(back()->getTargetUrl());
            }
        }
    }

    private static function validateURLFeature(Request $request, Category|Post $parent)
    {
        foreach ($parent->fields as $field) {
            $isFieldPrefixIsNotURL = $field->prefix == url('');
            if (!$field->url && $isFieldPrefixIsNotURL) {
                session()->flash('error', $field->label . ' alan öneği uygulamanın URL\'ı olmak zorundadır!');
                throw ValidationException::withMessages([])->redirectTo(back()->getTargetUrl());
            }
        }
    }

    private function getImagesDirPath()
    {
        return 'assets/posts/images/';
    }

    private static function tryToLogToSitemap()
    {
        Artisan::call('app:log-to-sitemap');
    }

    public function handlePublish(Post $post)
    {
        if (!$post->category->have_details) return false;
        $publishDate = request()->input('publish_date');
        $now = now();
        if ($publishDate) {
            $publishDate = Carbon::parse($publishDate);
            $post->updateQuietly([
                'publish_date' => $publishDate,
                'published' => ($publishDate <= $now)
            ]);
        } else {
            $post->updateQuietly([
                'publish_date' => $now,
                'published' => true
            ]);
        }
    }

    public function handleSpecialCategoryPostFeaturesOnCreate(Post $post)
    {
        
        if (Category::find($post->category_id)->title == 'Home Sections')
            $this::tryToCreateNewSection($post);
    }

    public static function tryToCreateNewSection(Post $post)
    {
        $post_view = $post->field('view');
        if (!$post_view) throw new BadMethodCallException;
        $file_path = 'resources/views/front/partials/sections/' . $post_view . '.blade.php';
        if (!File::exists($file_path))
            File::put(base_path('resources/views/front/partials/sections/' . $post->field('view') . '.blade.php'), '{{ $section->field("view") }}');
    }

    public function handleSpecialCategoryPostFeaturesOnDelete(Post $post)
    {
        if (Category::find($post->category_id)->title == 'Home Sections')
            $this::tryToDeleteSection($post);
    }

    public static function tryToDeleteSection(Post $post)
    {
        $post_view = $post->field('view');
        if (!$post_view) throw new BadMethodCallException;
        $file_path = 'resources/views/front/partials/sections/' . $post_view . '.blade.php';
        if (File::exists($file_path))
            File::delete(base_path('resources/views/front/partials/sections/' . $post->field('view') . '.blade.php'));
    }

}
