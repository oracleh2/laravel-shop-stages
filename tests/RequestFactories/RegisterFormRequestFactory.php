<?php

namespace Tests\RequestFactories;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Worksome\RequestFactories\RequestFactory;

class RegisterFormRequestFactory extends RequestFactory
{

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password(8),
            'password_confirmation' => $this->faker->password,
        ];
    }
}
