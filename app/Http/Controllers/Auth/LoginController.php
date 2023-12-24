<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

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
