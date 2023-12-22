<?php

namespace Domain\Auth\Routing;

use App\Contracts\RouteRegistrar;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\SocialiteGithubController;
use App\Http\Controllers\HomeController;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\Facades\Route;

class AuthRegistrar implements RouteRegistrar
{

    public function map(Registrar $registrar): void
    {
        Route::middleware('web')->group( function () {
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
        });
    }
}
