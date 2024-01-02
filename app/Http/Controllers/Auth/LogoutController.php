<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Support\SessionRegenerator;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        SessionRegenerator::run(fn() => auth()->logout());

        return redirect()->route('login-mail-show');
    }
}
