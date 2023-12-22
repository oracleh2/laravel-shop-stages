<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Image;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ThumbnailController extends Controller
{
    public function __invoke(string $dir, string $method, string $size, string $file): BinaryFileResponse
    {
//        dd(config('thumbnail.allowed_sizes', []));
        abort_if(!in_array($size, config('thumbnail.allowed_sizes', [])), 404);

        $storage = Storage::disk('images');
        $realPath = "$dir/$file";
        $newDirPath = "$dir/$method/$size";
        $resultPath = "$newDirPath/$file";

        if(!$storage->exists($newDirPath))
            $storage->makeDirectory($newDirPath);

        if(!$storage->exists($resultPath)){
            $manager = new ImageManager(
                new GdDriver()
            );
            $image = $manager->read($storage->path($realPath));
            [$width, $height] = explode('x', $size);
            $image->{$method}($width, $height);
            $encoded = $image->toPng();
            $encoded->save($storage->path($resultPath));
        }

        return response()->file($storage->path($resultPath));
    }
}
