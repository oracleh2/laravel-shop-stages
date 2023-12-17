<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class RefreshCommand extends Command
{
    protected $signature = 'refresh';
    protected $description = 'Clear and refresh the application files and database seeds';
    public function handle():void
    {
        if(app()->isProduction())
            return;

        File::cleanDirectory(storage_path('app/public/images/products'));
        $this->call('migrate:fresh', [
            '--seed' => true
        ]);
    }
}
