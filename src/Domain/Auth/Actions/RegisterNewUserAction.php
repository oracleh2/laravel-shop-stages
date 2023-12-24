<?php

namespace Domain\Auth\Actions;

use App\Http\Requests\RegisterFormRequest;
use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterNewUserAction implements RegisterNewUserContract
{
    public function __invoke(NewUserDTO $newUserDTO): User
    {
        $user = User::query()->create([
            'name' => $newUserDTO->name,
            'email' => $newUserDTO->email,
            'password' => Hash::make($newUserDTO->password),
        ]);

        event(new Registered($user));
        return $user;
    }
}
