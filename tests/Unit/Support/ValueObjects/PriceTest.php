<?php

namespace Support\ValueObjects;

use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class PriceTest extends TestCase
{
    use RefreshDatabase;
     /** @test */
     public function it_all():void
     {
         $price = Number::make('100');

         $this->assertInstanceOf(Number::class, $price);
         $this->assertEquals(100, $price->value());
         $this->assertEquals('RUB', $price->currency());
         $this->assertEquals('₽', $price->currencySymbol());
         $this->assertEquals('100 ₽', $price->price());
         $this->assertEquals('100', $price);

         $this->expectException(InvalidArgumentException::class);
         Number::make('-100');
         Number::make('100', 'USD');
     }
}
