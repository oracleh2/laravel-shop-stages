<?php

namespace Support\Testing;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;

class FakerImageProvider extends Base
{
    public function fixturesImage( string $fixturesDir, string $storageDir, bool $fullPath = false ): string
    {
        $storage = Storage::disk('images');

        if(!$storage->exists($storageDir))
            $storage->makeDirectory($storageDir);

        $file = $this->generator->file(
            base_path("tests/Fixtures/images/$fixturesDir"),
            $storage->path($storageDir),
            $fullPath
        );

        return '/storage/images/' . trim($storageDir, '/') . '/' . $file;
    }
}
