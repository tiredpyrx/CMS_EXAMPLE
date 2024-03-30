<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Support\Facades\File as FacadesFile;

class FileService
{
    public function deleteFile(File $media): bool
    {
        if (!$media->source) return false;
        $actions = [];
        $actions[] = $this->deleteFileFromPublicDir($media->source);
        $actions[] = $media->child()->delete();
        $actions[] = $media->delete();
        return !in_array(false, $actions);
    }

    public function deleteFileFromPublicDir($path)
    {
        $path = public_path($path) === $path ? $path : public_path($path);
        return FacadesFile::delete($path);
    }
}