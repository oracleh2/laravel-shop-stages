<?php

namespace App\Http\Controllers\Auth;

use Database\Factories\UserFactory;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Mockery\MockInterface;
use Tests\TestCase;


class SocialiteControllerTest extends TestCase
{
    use RefreshDatabase;

    private function mockSocialiteCallback(int|string $github_id): MockInterface
    {
        $user = $this->mock(SocialiteUser::class, function(MockInterface $mock) use ($github_id) {
            $mock->shouldReceive('getId')
                ->once()
                ->andReturn($github_id);
            $mock->shouldReceive('getName')
                ->once()
                ->andReturn(str()->random(10));
            $mock->shouldReceive('getEmail')
                ->once()
                ->andReturn('admin@gmail.ru');
        });

        Socialite::shouldReceive('driver->user')
            ->once()
            ->andReturn($user);
        return $user;
    }
    private function callbackRequest(): TestResponse
    {
        return $this->get(
            action(
                [SocialiteController::class, 'callback'],
                ['driver' => 'github']
            )
        )
            ->assertRedirectToRoute('index')
            ;
    }
    /**
     * @test
     */
    public function it_github_redirect_success(): void
    {

        $this->get(
            action(
                [SocialiteController::class, 'redirect'],
                ['driver' => 'github']
            )
        )->assertRedirectContains('github.com');
    }
    /**
     * @test
     */
    public function it_driver_not_found_exception(): void
    {
        $this->expectException(DomainException::class);
        $this
            ->withoutExceptionHandling()
            ->get(
                action(
                    [SocialiteController::class, 'redirect'],
                    ['driver' => 'vk']
                )
            );

        $this
            ->withoutExceptionHandling()
            ->get(
                action(
                    [SocialiteController::class, 'callback'],
                    ['driver' => 'vk']
                )
            );
    }

    /**
     * @test
     */
    public function it_github_callback_created_user_success(): void
    {
        $github_id = str()->random(10);
        $this->assertDatabaseMissing('users', ['github_id' => $github_id]);
        $this->mockSocialiteCallback($github_id);
        $this->callbackRequest()
            ->assertRedirect(route('index'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['github_id' => $github_id]);
    }
    /**
     * @test
     */
    public function it_authenticated_by_exiting_user(): void
    {
        $github_id = str()->random(10);
        UserFactory::new()->create([
            'github_id' => $github_id,
        ]);
        $this->assertDatabaseHas('users', ['github_id' => $github_id]);

        $this->mockSocialiteCallback($github_id);

        $this->callbackRequest()
            ->assertRedirect(route('index'));
        $this->assertAuthenticated();
    }
}
