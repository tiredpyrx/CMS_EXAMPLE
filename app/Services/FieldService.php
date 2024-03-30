<?php

namespace App\Services;

use App\Actions\GetUpdatedDatas;
use App\Actions\SaveUploadedFileToPublicDir;
use App\Enums\FieldTypes;
use App\Models\Category;
use App\Models\Field;
use App\Models\File;
use App\Models\Post;
use App\Observers\FieldObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Validation\ValidationException;

class FieldService
{
    public function create(array $safeRequest, Model $model): Field
    {
        $this->validate($safeRequest, $model->id);

        $additional = ['user_id' => auth()->id(), 'category_id' => $model->id];
        $merged = array_merge($safeRequest, $additional);
        if ($safeRequest['type'] === 'select')
            $safeRequest['as_option'] = true;

        $field = Field::create($merged);

        if ($safeRequest['type'] === 'image')
            $this->tryToUploadImage($safeRequest, $field, $model->id);
        else if ($safeRequest['type'] === 'images')
            $this->tryToUploadImages($safeRequest, $field, $model->id);

        (new FieldObserver())->customCreated($field);

        return $field;
    }

    public function getDetailedArray(int $user_id, string $parent_key, int $parent_id): array
    {
        $records = Field::DETAILED_RECORDS;
        foreach ($records as &$record) {
            $record['user_id'] = $user_id;
            $record[$parent_key] = $parent_id;
        };

        return $records;
    }

    public function updateFields(Field $field, array $safeRequest): bool
    {
        $this->validate($safeRequest, $field->category_id, $field);

        $isImageExists = $this->validateImage($safeRequest);
        if ($isImageExists)
            $this->tryToUploadImage($safeRequest, $field, $field->category_id);
        else if (isset($safeRequest['images']))
            $this->tryToUploadImages($safeRequest, $field, $field->category_id);

        $updated = (new GetUpdatedDatas())->execute($safeRequest, 'field', $field->id);
        $posts = Post::where('category_id', $field->category_id)->get();
        $actionsDummy = [];

        foreach ($posts as $post) {
            $pField = $post->fields()->where('handler', $safeRequest['handler'])->first();
            foreach ($safeRequest as $key => $value) {
                $keyIsTypeAttribute = ($key === 'type');
                $pFieldDoesntHaveValueYet = !$pField->getAttribute('value');
                $keyIsValueAttribute = ($key === 'value');
                if ($keyIsValueAttribute) {
                    if ($pFieldDoesntHaveValueYet)
                        $actionsDummy[] = $pField->update(['value' => $value]);
                } else if ($keyIsTypeAttribute) {
                    $featuresFieldCannotHave = array_diff(FieldTypes::getFeaturesForType($pField->type), FieldTypes::getFeaturesForType($value));
                    foreach ($featuresFieldCannotHave as $feature) {
                        $actionsDummy[] = $field->update([$feature => null]);
                        $actionsDummy[] = $pField->update([$feature => null]);
                    }
                    if (in_array($pField->type, Field::TYPES_WITH_CHILDREN)) {
                        $pField->fields()->each(fn ($f) => ($f->forceDelete()));
                        $field->fields()->each(fn ($pf) => ($pf->forceDelete()));
                    }
                    $actionsDummy[] = $pField->update(['type' => $value]);
                } else {
                    $pField->update([$key => $value]);
                }
            }
        }
        if (isset($featuresFieldCannotHave))
            $safeRequest = collect($safeRequest)->except($featuresFieldCannotHave)->toArray();

        $actionsDummy[] = $field->update($safeRequest);
        $actions = [];
        foreach ($actionsDummy as $key => $value) {
            $actions[++$key] = $value;
        }
        return !array_search(false, $actions);
    }

    public function validate(array $safeRequest, int $category_id, ?Field $field = new Field)
    {
        $this->isColumnValueAcceptable($safeRequest);
        $this->isUpcomingHandlerUnique($safeRequest, $category_id, $field);
        return 1;
    }

    public function isColumnValueAcceptable(array $safeRequest)
    {
        if ((3 <= (int)$safeRequest['column'] && (int)$safeRequest['column'] <= 12))
            return true;

        session()->flash('error', 'Alan kolon uzunluğu birim olarak 2\'den büyük ve 13\'den küçük (2,13) olmak zorundadır!');
        throw ValidationException::withMessages([])
            ->redirectTo(
                back()->getTargetUrl()
            );
    }

    public function isUpcomingHandlerUnique(array $safeRequest, int $category_id, Field $field = new Field)
    {
        $isFieldHandlerAlreadyExistsOnParentCategory = Category::find($category_id)
            ->fields()
            ->where('handler', $safeRequest['handler'])
            ->exists();

        if ($isFieldHandlerAlreadyExistsOnParentCategory && $field->handler != $safeRequest['handler']) {
            session()
                ->flash(
                    'error',
                    'Kategorinin alanlarında ' . $safeRequest['handler'] . ' işeyicisine sahip bir alan var!'
                );
            throw ValidationException::withMessages([])
                ->redirectTo(
                    back()->getTargetUrl()
                );
        }
    }

    public function validateImage(array $safeRequest)
    {
        if (!isset($safeRequest['image'])) return null;

        $image = $safeRequest['image'];

        $rules = [
            in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'webp', 'avif', 'svg']),
        ];

        return 1;
    }

    public function tryToUploadImage(array $safeRequest, Field $field, int $categoryId)
    {
        if (!isset($safeRequest['image'])) return null;

        $image = $safeRequest['image'];
        $oldImageFileRecord = $field->firstFile();
        if ($oldImageFileRecord)
            return $this->tryToUpdateImage($oldImageFileRecord, $image);
        $imagePath = $this->getImageDirPath();
        $imageSource = (new SaveUploadedFileToPublicDir())->execute($image, $imagePath);
        $file = $field->files()->create([
            'user_id' => auth()->id(),
            'category_id' => $categoryId,
            'title' => isset($safeRequest['image_title']) ? $safeRequest['image_title'] : '',
            'description' => isset($safeRequest['image_description']) ? $safeRequest['image_description'] : '',
            'source' => $imageSource,
            'handler' => $field->handler,
        ]);
        return $file;
    }

    public function tryToUploadImages(array $safeRequest, Field $field, int $categoryId)
    {
        if (!isset($safeRequest['images'])) return null;

        $images = $safeRequest['images'];
        $imageDirPath = $this->getImageDirPath();
        foreach ($images as $image) {
            $imageSource = (new SaveUploadedFileToPublicDir())->execute($image, $imageDirPath);
            $field->files()->create([
                'user_id' => auth()->id(),
                'category_id' => $categoryId,
                'title' => isset($safeRequest['image_title']) ? $safeRequest['image_title'] : '',
                'description' => isset($safeRequest['image_description']) ? $safeRequest['image_description'] : '',
                'source' => $imageSource,
                'handler' => $field->handler,
            ]);
        }
    }

    public function tryToUpdateImage(File $oldImageFileRecord, UploadedFile $uploadedImage)
    {
        $this->tryToDeleteOldImageFromPublicDir($oldImageFileRecord->source);
        $imagePath = $this->getImageDirPath();
        $newImageSource = (new SaveUploadedFileToPublicDir())->execute($uploadedImage, $imagePath);
        return $oldImageFileRecord->update(['source' => $newImageSource]);
    }

    public function tryToDeleteOldImageFromPublicDir(string $path): bool
    {
        if (!$this->isFileExists($path)) return false;
        $path = $this->getPublicPath($path);
        return FacadesFile::delete($path);
    }

    public function isFileExists(string $path): bool
    {
        $path = $this->getPublicPath($path);
        return FacadesFile::exists($path);
    }

    public function getPublicPath(string $path)
    {
        return match ($path) {
            public_path($path) => $path,
            default => public_path($path),
        };
    }

    public function getImageDirPath()
    {
        return 'assets/fields/images/';
    }

    public function syncImages(Field $field)
    {
        foreach ($field->category->posts as $post) {
            $pField = $post->fields()->where('handler', $field->handler)->first();
            $pFieldFilesAreSameAsFieldFiles = true;
            for ($i = 0; $i < $pField->files->count(); $i++) {
                $pFieldFile = $pField->files[$i];
                if (!in_array($pFieldFile->source, $field->files->pluck('source')->toArray()))
                    $pFieldFilesAreSameAsFieldFiles = false;
            }
            if (!$pField->files->count() || $pFieldFilesAreSameAsFieldFiles) {
                $pField->files->each(fn ($file) => $file->delete());
                foreach ($field->files as $file) {
                    $newFile = $file->replicate(['field_id', 'file_id']);
                    $newFile->field_id = $pField->id;
                    $newFile->file_id = $file->id;
                    $newFile->save();
                    $pField->files()->save($newFile);
                }
            }
        }
    }

    public function appendToTrash(Field $field)
    {
        $field->fields()->each(fn ($field) => $field->delete());
        $field->files()->each(fn ($file) => $file->delete());
        foreach ($field->category->posts as $post) {
            $pField = $post->fields()->where('handler', $field->handler)->first();
            if (count($pField->fields))
                $pField->fields->each(fn ($child) => $child->delete());
            if (count($pField->files))
                $pField->files()->each(fn ($file) => $file->delete());
            $pField->delete();
        }
        return $field->delete();
    }
}
