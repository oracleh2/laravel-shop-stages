<?php

namespace Auth\Actions;

use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterNewUserActionTest extends TestCase
{
    use RefreshDatabase;
     /** @test */
     public function it_success_user_created():void
     {
         $this->assertDatabaseMissing('users', ['email' => 'kek@ya.ru']);
         $action = app(RegisterNewUserContract::class);
         $action(NewUserDTO::make('kek', 'kek@ya.ru', '12312344asd!'));
         $this->assertDatabaseHas('users', ['email' => 'kek@ya.ru']);
     }

}
