<?php

namespace Auth\DTOs;

use App\Http\Requests\RegisterFormRequest;
use Domain\Auth\DTOs\NewUserDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewUserDTOTest extends TestCase
{
    use RefreshDatabase;



     /** @test */
     public function it_instance_created_from_request():void
     {
         $dto = NewUserDTO::fromRequest(new RegisterFormRequest([
             'name' => 'kek',
             'email' => 'kek@ya.ru',
             'password' => '12312344asd!',
         ]));
         $this->assertInstanceOf(NewUserDTO::class, $dto);
     }

}
