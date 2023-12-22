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


class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_login_page_success(): void
    {
        $this->get(action([LoginController::class, 'loginShow']))
            ->assertOk()
            ->assertViewIs('auth.login')
            ->assertSee('Вход в аккаунт')
        ;
    }
    /** @test */
    public function it_register_page_success(): void
    {
        $this->get(action([RegisterController::class, 'registerShow']))
            ->assertOk()
            ->assertViewIs('auth.register')
            ->assertSee('Регистрация')
        ;
    }
    /** @test */
    public function it_forgot_page_success(): void
    {
        $this->get(action([ForgotPasswordController::class, 'forgotPasswordShow']))
            ->assertOk()
            ->assertViewIs('auth.forgot-password')
            ->assertSee('Восстановление пароля')
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
    public function it_store_success(): void
    {
        Event::fake();
        Notification::fake();

        $request = RegisterFormRequest::factory()->create([
            'email' => 'kek@ya.ru',
            'password' => '12312344',
            'password_confirmation' => '12312344'
        ]);

        $this->assertDatabaseMissing('users', ['email' => $request['email']]);

        $response = $this->post(
            action([RegisterController::class, 'registerMailSubmit']),
            $request
        );
        $response->assertValid();


        $this->assertDatabaseHas('users', ['email' => $request['email']]);

        $user = User::query()->where('email', $request['email'])->first();

        Event::assertDispatched(Registered::class);
        Event::assertListening(Registered::class, SendEmailNewUserListener::class);

        $event = new Registered($user);
        $listener = new SendEmailNewUserListener();
        $listener->handle($event);

        Notification::assertSentTo($user, NewUserNotification::class);

        $response->assertRedirect(route('index'));

        $this->assertAuthenticatedAs($user);
    }
    /** @test */
    public function it_logout_success(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'kek@ya.ru',
            'password' => Hash::make('12312344'),
        ]);
//        $this->assertAuthenticatedAs($user);
        $response = $this
            ->actingAs($user)
            ->delete(
                action([LogoutController::class, 'logout'])
            );
        $this->assertGuest();

        $response->assertRedirect(route('login-mail-show'));
    }
}
