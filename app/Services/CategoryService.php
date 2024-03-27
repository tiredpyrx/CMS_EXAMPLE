<?php

namespace App\Services;

use App\Actions\FilterRequest;
use App\Enums\PageBladeTemplate;
use App\Models\Category;
use App\Models\Field;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;
use PHPUnit\TextUI\XmlConfiguration\RemoveCoverageElementProcessUncoveredFilesAttribute;
use Spatie\Activitylog\Facades\LogBatch;

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
        $view = $this->getViewFullPath($fileName);
        if (View::exists($view)) return false;

        return File::put(base_path($view), $content);
    }

    public function tryToDeleteViewFile(string $fileName)
    {
        $viewPath = $this->getViewFullPath($fileName);
        if (!File::exists(base_path($viewPath))) return false;

        return File::delete(base_path($viewPath));
    }

    public function tryToChangeView($oldFileName, $newFileName)
    {
        $oldViewPath = $this->getViewFullPath($oldFileName);
        $isOldViewExists = isFileExists($oldViewPath);
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
        if (isFileExists($path))
            return File::get(base_path($path));
        return null;
    }

    public function getViewFullPath(string $fileName)
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
