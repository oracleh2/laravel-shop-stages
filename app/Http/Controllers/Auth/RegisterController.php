<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterFormRequest;
use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Illuminate\Support\Facades\Auth;

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
        $user = $action(NewUserDTO::fromRequest($request));
        Auth::login($user);

        return redirect()->intended(route('index'));
    }
}
