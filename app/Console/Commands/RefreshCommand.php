<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class RefreshCommand extends Command
{
    protected $signature = 'refresh';
    protected $description = 'Clear and refresh the application files and database seeds';
    public function handle(): int
    {
        if(app()->isProduction())
            return self::FAILURE;

        Artisan::call('cache:clear');
        Storage::deleteDirectory('images/products');
        Storage::deleteDirectory('images/brands');
        $this->call('migrate:fresh', [
            '--seed' => true
        ]);

        return self::SUCCESS;
    }
}
