<?php

namespace App\Utilities;

use App\Models\Common\Media;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\ImageManager;

class Images
{
    /**
     * Resize an image and keep the encoded bytes in the cache for a few minutes,
     * which is what the old Image::cache() helper of intervention/imagecache did.
     *
     * @throws \Exception when the source cannot be decoded
     */
    public static function resize(?Media $media, string $path, $width, $height): string
    {
        $key = 'images.resize.' . md5(implode('|', [
            $media?->id,
            $media?->updated_at,
            $path,
            $width,
            $height,
        ]));

        return Cache::remember($key, now()->addMinutes(5), function () use ($media, $path, $width, $height) {
            $image = ImageManager::gd()->read($media ? $media->contents() : $path);

            return (string) $image->resize((int) $width ?: null, (int) $height ?: null)->encode();
        });
    }
}
