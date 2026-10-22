<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PublicImage
{
    public static function store(UploadedFile $image, string $directory): string
    {
        $path = $image->store($directory, 'public');

        return Storage::disk('public')->url($path);
    }

    public static function delete(?string $url, string $directory): void
    {
        if (!$url) {
            return;
        }

        $path = parse_url($url, PHP_URL_PATH);
        $prefix = '/storage/'.$directory.'/';
        if (!is_string($path) || !str_starts_with($path, $prefix)) {
            return;
        }

        Storage::disk('public')->delete(substr($path, strlen('/storage/')));
    }
}
