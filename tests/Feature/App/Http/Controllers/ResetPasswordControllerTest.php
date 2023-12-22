<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
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
use Illuminate\Support\Facades\Password;
use Tests\TestCase;


class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $token;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->user = UserFactory::new()->create(['email' => 'kek@ya.ru']);
        $this->token = Password::createToken($this->user);
    }


    /**
     * @test
     */
    public function it_show_page_success(): void
    {
        $this->get(action([ResetPasswordController::class, 'resetPasswordShow'], $this->token))
            ->assertOk()
            ->assertViewIs('auth.reset-password')
            ->assertSee('Сбросить пароль');
    }
    /**
     * @test
     */
    public function it_submit_page_success(): void
    {
        $password = '1234567890asd!';
        $password_confirmation = '1234567890asd!';

        Password::shouldReceive('reset')
            ->once()
            ->withSomeOfArgs([
                'email' => $this->user->email,
                'password' => $password,
                'password_confirmation' => $password_confirmation,
                'token' => $this->token
            ])
            ->andReturn(Password::PASSWORD_RESET);

        $response = $this->post(action([ResetPasswordController::class, 'resetPasswordSubmit']), [
            'email' => $this->user->email,
            'password' => $password,
            'password_confirmation' => $password_confirmation,
            'token' => $this->token
        ]);

        $response->assertRedirect(action([LoginController::class, 'loginShow']));


    }
}