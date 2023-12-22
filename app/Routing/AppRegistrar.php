<?php

namespace App\Routing;

use App\Contracts\RouteRegistrar;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ThumbnailController;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\Facades\Route;

class AppRegistrar implements RouteRegistrar
{

    public function map(Registrar $registrar): void
    {
        Route::middleware('web')->group( function () {
            Route::get('/', [HomeController::class, 'index'])->name('index');
            Route::get('/storage/images/{dir}/{method}/{size}/{file}', ThumbnailController::class)
                ->where('method', 'thumb|crop|fill|fit|resize')
                ->where('size', '\d+x\d+')
                ->where('file', '.+\.(jpg|jpeg|png|gif|svg)$')
                ->name('thumbnail');

//            Route::get('/storage/images/{dir}/{method}/{size}/{file}', function(string $dir, string $method, string $size, string $file) {
//                dd($dir, $method, $size, $file);
//            })
////                ->where('method', 'thumb|crop|fill|fit|resize')
////                ->where('size', '\d+x\d+')
////                ->where('file', '.+\.(jpg,jpeg,png,gif,svg)$')
//                ->name('thumbnail');
        });
    }
}
