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


class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_login_page_success(): void
    {
        $this->get(action([LoginController::class, 'loginShow']))
            ->assertOk()
            ->assertViewIs('auth.login')
//            ->assertSee('Вход в аккаунт')
//            ->assertSee('Почта')
//            ->assertSee('GitHub')
        ;
    }
    /** @test */
    public function it_login_mail_page_success(): void
    {
        $this->get(action([LoginController::class, 'loginMailShow']))
            ->assertOk()
            ->assertViewIs('auth.login-mail')
            ->assertSee('Вход в аккаунт')
        ;
    }
    /** @test */
    public function it_login_submit_success(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'kek@ya.ru',
            'password' => Hash::make('12312344'),
        ]);
        $request = LoginFormRequest::factory()->create([
            'email' => 'kek@ya.ru',
            'password' => '12312344',
        ]);

        $response = $this->post(
            action([LoginController::class, 'loginMailSubmit']),
            $request
        );
        $this->assertDatabaseHas('users', ['email' => $request['email']]);
        $response
            ->assertValid()
            ->assertRedirect( route('index'));

        $this->assertAuthenticatedAs($user);
    }
    /** @test */
    public function it_login_submit_fail(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'kek@ya.ru',
            'password' => Hash::make('12312344'),
        ]);
        $request = LoginFormRequest::factory()->create([
            'email' => 'kek@ya.ru',
            'password' => '123',
        ]);

        $response = $this->post(
            action([LoginController::class, 'loginMailSubmit']),
            $request,
            ['HTTP_REFERER' => action([LoginController::class, 'loginMailShow'])]
        );
        $this->assertDatabaseMissing('users', [
            'email' => $request['email'],
            'password' => $request['password'],
        ]);
        $response
            ->assertInvalid(['email']);


        $response
            ->assertRedirect( action([LoginController::class, 'loginMailShow']));

        $this->assertGuest();
    }
}
