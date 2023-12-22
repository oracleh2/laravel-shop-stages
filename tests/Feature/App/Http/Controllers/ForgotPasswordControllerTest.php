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
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;


class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    private function testingCredentials(): array
    {
        return [
            'email' => 'kek@ya.ru',
        ];
    }

    /** @test * */
    public function it_forgot_page_success(): void
    {
        $this->get(action([ForgotPasswordController::class, 'forgotPasswordShow']))
            ->assertOk()
            ->assertViewIs('auth.forgot-password')
            ->assertSee('Восстановление пароля');
    }

    /** @test * */
    public function it_forgot_page__submit_success(): void
    {
        $user = UserFactory::new()->create($this->testingCredentials());
        $this->post(action([ForgotPasswordController::class, 'forgotPasswordSubmit']), $this->testingCredentials())
            ->assertRedirect();
        Notification::assertSentTo($user, ResetPassword::class);
    }

    /** @test * */
    public function it_forgot_page__submit_fail(): void
    {

        $this->post(action([ForgotPasswordController::class, 'forgotPasswordSubmit']), $this->testingCredentials())
            ->assertInvalid(['email']);
        Notification::assertNothingSent();
    }
}
