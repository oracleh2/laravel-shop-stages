<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class InstallCommand extends Command
{
    protected $signature = 'install';
    protected $description = 'Install the application';
    public function handle(): int
    {
        if(app()->isProduction())
            return self::FAILURE;

//        File::cleanDirectory(storage_path('app/public/images/products'));
        Artisan::call('key:generate');
        Artisan::call('storage:link');
        Artisan::call('telescope:install');
        Artisan::call('refresh');

        return self::SUCCESS;
    }
}
