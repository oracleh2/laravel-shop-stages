<?php

namespace App\Http\Controllers\Auth;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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
