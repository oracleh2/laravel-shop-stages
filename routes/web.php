<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialiteGithubController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', [HomeController::class, 'index'])->name('index');
//Route::controller(LoginController::class)->group(function () {
//    Route::get('/login', 'loginShow')->name('login');
//    Route::get('/login-mail', 'loginMailShow')->name('login-mail-show');
//    Route::post('/login-mail', 'loginMailSubmit')->name('login-mail-submit');
//});
//Route::controller(RegisterController::class)->group(function () {
//    Route::get('/register', 'registerShow')->name('register');
//    Route::get('/register-mail', 'registerMailShow')->name('register-mail-show');
//    Route::post('/register-mail', 'registerMailSubmit')->name('register-mail-submit');
//});
//Route::controller(LogoutController::class)->group(function () {
//    Route::get('/logout', 'logout')->name('logout');
//});
//Route::controller(ForgotPasswordController::class)->group(function () {
//    Route::get('/forgot-password', 'forgotPasswordShow')->middleware('guest')->name('password.request');
//    Route::post('/forgot-password', 'forgotPasswordSubmit')->middleware('guest')->name('password.email');
//});
//Route::controller(ResetPasswordController::class)->group(function () {
//    Route::get('/reset-password/{token}', 'resetPasswordShow')->middleware('guest')->name('password.reset');
//    Route::post('/reset-password', 'resetPasswordSubmit')->middleware('guest')->name('password.update');
//});
//Route::controller(SocialiteGithubController::class)->group(function () {
//    Route::get('auth/github/redirect', 'githubRedirect')->name('socialite.github.redirect');
//    Route::get('auth/github/callback', 'githubCallback')->name('socialite.github.callback');
//});



