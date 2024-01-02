<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\Auth\Models\User;
use DomainException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Support\SessionRegenerator;
use Throwable;

class SocialiteController extends Controller
{
    public function redirect(string $driver = 'github')
    {
        try{
            return Socialite::driver($driver)->redirect();
        }
        catch (Throwable $exception){
            throw new DomainException('Произошла ошибка или драйвер не поддерживается');
        }
    }
    public function callback(string $driver = 'github')
    {
        if($driver != 'github'){
            throw new DomainException('Драйвер не поддерживается');
        }

        $socialiteUser = Socialite::driver($driver)->user();

        $user = User::query()->updateOrCreate([
            $driver . '_id' => $socialiteUser->getId(),
        ], [
            'name' => $socialiteUser->getName(),
            'email' => $socialiteUser->getEmail(),
            'password' => Hash::make('12312344'),
//            'github_token' => $socialiteUser->token,
//            'github_refresh_token' => $socialiteUser->refreshToken,
        ]);

        SessionRegenerator::run(fn() => auth()->login($user));

        return redirect()->route('index');
    }
}
