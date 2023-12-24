<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordFormRequest;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function forgotPasswordShow()
    {
        return view('auth.forgot-password');
    }
    public function forgotPasswordSubmit(ForgotPasswordFormRequest $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );
        if($status === Password::RESET_LINK_SENT){
            flash()->info(__($status));
            return back();
        }
        flash()->alert(__($status));
        return back()->withErrors(['email' => __($status)]);
    }
}
