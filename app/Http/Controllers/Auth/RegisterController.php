<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordFormRequest;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Requests\ResetPasswordFormRequest;
use Domain\Auth\Actions\RegisterNewUserAction;
use Domain\Auth\Contracts\RegisterNewUserContract;
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

class RegisterController extends Controller
{
    public function registerShow()
    {
        return view('auth.register');
    }
    public function registerMailShow()
    {
        return view('auth.register-mail');
    }
    public function registerMailSubmit(RegisterFormRequest $request, RegisterNewUserContract $action)
    {
        //RegisterNewUserAction::execute($request);
        $user = $action($request->name, $request->email, $request->password);
        Auth::login($user);

        return redirect()->intended(route('index'));
    }
}
