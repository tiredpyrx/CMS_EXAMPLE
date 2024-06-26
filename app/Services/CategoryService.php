<?php

namespace App\Services;

use App\Actions\FilterRequest;
use App\Enums\PageBladeTemplate;
use App\Models\Category;
use App\Models\Field;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class CategoryService
{
    public function create(Request $request)
    {
        $safeRequest = (new FilterRequest())->execute($request, 'category');
        $merged = $this->getMergedOnCreate($safeRequest);

        $fileName = $safeRequest['view'];
        if ($fileName) {
            $this->tryToCreateViewFile($fileName);
        }
        return Category::create($merged);
    }

    public function getDefaultViewContent()
    {
        return PageBladeTemplate::content();
    }

    public function tryToCreateViewFile(string $fileName, $content = null)
    {
        $content = $content ?: $this->getDefaultViewContent();
        $viewPath = $this::getViewFullPath($fileName);
        if ($this->isViewExists($viewPath)) return false;

        return File::put(base_path($viewPath), $content);
    }

    public function tryToDeleteViewFile(string $fileName)
    {
        $viewPath = $this::getViewFullPath($fileName);
        if (!$this->isViewExists($viewPath)) return false;

        return File::delete(base_path($viewPath));
    }

    public function isViewExists($viewPath)
    {
        return File::exists(base_path($viewPath));
    }

    public function tryToChangeView(?string $oldFileName, $newFileName)
    {
        if (!$oldFileName)
            return $this->tryToCreateViewFile($newFileName);

        $oldViewPath = $this::getViewFullPath($oldFileName);
        $isOldViewExists = $this::isFileExists($oldViewPath);
        if (!$isOldViewExists)
            return $this->tryToCreateViewFile($newFileName);

        $oldViewContent = $this->tryToReadViewContent($oldViewPath) ?: $this->getDefaultViewContent();

        $actions = [];
        $actions[] = $this->tryToDeleteViewFile($oldFileName);
        $actions[] = $this->tryToCreateViewFile($newFileName, $oldViewContent);
        return !in_array(false, $actions);
    }

    public function tryToReadViewContent(string $path): string|null
    {
        if ($this::isFileExists($path))
            return File::get(base_path($path));
        return null;
    }

    private static function isFileExists(?string $path)
    {
        if (!$path) return null;
        return File::exists(base_path($path));
    }

    private static function getViewFullPath(string $fileName)
    {
        return PageBladeTemplate::path() . $fileName . PageBladeTemplate::extension();
    }

    public function getMergedOnCreate(array $safeRequest)
    {
        $additional = ['user_id' => auth()->id()];
        return array_merge($safeRequest, $additional);
    }

    public function update(Category $category, array $updated)
    {
        $fileNameUpdated = isset($updated['view']);
        if ($fileNameUpdated) {
            $this->tryToChangeView($category->view, $updated['view']);
        }
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
        dd($category->posts);
        Artisan::call('app:log-to-sitemap');
        return $success;
    }

    public function deleteMany(array $ids)
    {
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

    public function forceDelete(Category $category)
    {
        $success = $category->forceDelete();
        if (!$success)
            return ['error', 'Kategori silinirken bir sorun oluştu!'];
        return ['success', 'Kategori başarıyla silindi!'];
    }
}
