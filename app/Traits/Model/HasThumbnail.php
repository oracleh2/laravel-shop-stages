<?php

namespace App\Traits\Model;

use Illuminate\Support\Facades\File;

trait HasThumbnail
{
    abstract protected function thumbnailDir(): string;
    public function makeThumbnail(string $size, string $method = 'resize'): string
    {
//        dd($method, $size, File::basename($this->{$this->thumbnailColumn()}));
        return route(
            'thumbnail', [
                'dir' => $this->thumbnailDir(),
                'method' => $method,
                'size' => $size,
                'file' => File::basename($this->{$this->thumbnailColumn()})
            ]);
    }
    protected function thumbnailColumn(): string
    {
        return 'thumbnail';
    }
}
