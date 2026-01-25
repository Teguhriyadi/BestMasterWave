<?php

namespace App\Http\Repositories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FileRepo {
    public function saveFile($file, $path)
    {
        try {
            if ($file instanceof \Illuminate\Http\UploadedFile) {
                $extension = $file->getClientOriginalExtension();
                $filename = Str::random(40) . '.' . $extension;
                $fullPath = $path . $filename;

                Storage::disk('s3')->put($fullPath, file_get_contents($file->getRealPath()), 'public');
            } else {
                $filename = Str::random(40) . '.png';
                $fullPath = $path . $filename;

                Storage::disk('s3')->put($fullPath, $file, 'public');
            }

            return Storage::disk('s3')->url($fullPath);
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
}
