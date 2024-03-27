<?php

namespace App\Actions;

use Illuminate\Http\UploadedFile;

class SaveUploadedFileToPublicDir
{
    public function execute(?UploadedFile $file, string $path)
    {
        if (!$file) return null;
        $fileHexName = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
        $imageSource = $path .  $fileHexName;
        $file->move(public_path($path), $fileHexName);
        return $imageSource;
    }
}
