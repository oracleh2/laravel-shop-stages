<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordFormRequest;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Requests\ResetPasswordFormRequest;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function loginShow()
    {
        return view('auth.login');
    }
    public function loginMailShow()
    {
        return view('auth.login-mail');
    }
    public function loginMailSubmit(LoginFormRequest $request): RedirectResponse
    {
        if(!Auth::attempt($request->validated())){
            return back()->withErrors([
                'email' => 'Пользователь с такими email и паролем не найден.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('index'));
    }
}
