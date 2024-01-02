<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\SocialiteGithubController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ThumbnailController;
use App\Http\Middleware\CatalogViewMiddleware;
use Illuminate\Support\Facades\Route;


Route::get('/', HomeController::class)->name('index');

Route::get('/catalog/{category:slug?}', CatalogController::class)
    ->middleware(CatalogViewMiddleware::class)
    ->name('catalog');

Route::get('/product/{product:slug}', ProductController::class)
    ->name('product');

Route::controller(CartController::class)
    ->prefix('cart')
    ->name('cart.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{product}/add', 'add')->name('add');
        Route::post('/{cartItem}/quantity', 'quantity')->name('quantity');
        Route::delete('/{cartItem}/delete', 'delete')->name('delete');
        Route::delete('/truncate', 'truncate')->name('truncate');
    });

Route::controller(OrderController::class)
    ->prefix('order')
    ->name('order.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'handle')->name('handle');
    });


Route::get('/storage/images/{dir}/{method}/{size}/{file}', ThumbnailController::class)
    ->where('method', 'thumb|crop|fill|fit|resize')
    ->where('size', '\d+x\d+')
    ->where('file', '.+\.(jpg|jpeg|png|gif|svg)$')
    ->name('thumbnail');


Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'loginShow')->name('login');
    Route::get('/login-mail', 'loginMailShow')->name('login-mail-show');
    Route::post('/login-mail', 'loginMailSubmit')
//                    ->middleware('guest')
        ->middleware('throttle:auth')
        ->name('login-mail-submit');
});
Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'registerShow')->name('register');
    Route::get('/register-mail', 'registerMailShow')->name('register-mail-show');
    Route::post('/register-mail', 'registerMailSubmit')
        ->middleware('throttle:auth')
        ->name('register-mail-submit');
});
Route::controller(LogoutController::class)->group(function () {
    Route::delete('/logout', 'logout')->name('logout');
});
Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('/forgot-password', 'forgotPasswordShow')->middleware('guest')->name('password.request');
    Route::post('/forgot-password', 'forgotPasswordSubmit')->middleware('guest')->name('password.email');
});
Route::controller(ResetPasswordController::class)->group(function () {
    Route::get('/reset-password/{token}', 'resetPasswordShow')->middleware('guest')->name('password.reset');
    Route::post('/reset-password', 'resetPasswordSubmit')->middleware('guest')->name('password.update');
});
Route::controller(SocialiteController::class)->group(function () {
    Route::get('auth/{driver}/redirect', 'redirect')->name('socialite.redirect');
    Route::get('auth/{driver}/callback', 'callback')->name('socialite.callback');
});
