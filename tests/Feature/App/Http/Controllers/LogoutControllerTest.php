<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;
use App\Listeners\SendEmailNewUserListener;
use App\Notifications\NewUserNotification;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;


class LogoutControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_logout_success(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'kek@ya.ru',
            'password' => Hash::make('12312344'),
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(
                action([LogoutController::class, 'logout'])
            );
        $this->assertGuest();

        $response->assertRedirect(route('login-mail-show'));
    }
    /** @test */
    public function it_logout_guest_middleware_fail(): void
    {
        $this->delete(action([LogoutController::class, 'logout']))
            ->assertRedirect(route('login-mail-show'));
    }
}
