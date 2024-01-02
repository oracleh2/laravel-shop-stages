<?php

namespace App\Http\Controllers;


use Database\Factories\ProductFactory;
use Domain\Cart\CartManager;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        CartManager::fake();
    }

    /** @test */
     public function it_empty_cart():void
     {
         $this->get(route('cart.index'))
             ->assertOk()
             ->assertStatus(200)
             ->assertViewIs('cart.index')
             ->assertViewHas('items', collect([]))
             ->assertSee('Корзина пуста');
     }

    /** @test */
    public function it_not_empty_cart():void
    {
        $product = ProductFactory::new()->create();
        cart()->add($product);
        $this->get(route('cart.index'))
            ->assertOk()
            ->assertStatus(200)
            ->assertViewIs('cart.index')
            ->assertViewHas('items', cart()->items())
            ;
    }
     /** @test */
     public function it_added_success():void
     {
         $product = ProductFactory::new()->create();
         $this->assertEquals(0, cart()->count());
         $this->post(route('cart.add', $product), ['quantity' => 4])
             ->assertStatus(302)
             ->assertRedirect()
             ->assertSessionHas('shop_flash_message', 'Товар добавлен в корзину');
         $this->assertEquals(4, cart()->count());
     }
    public function it_quantity_changed():void
    {
        $product = ProductFactory::new()->create();
        $this->assertEquals(0, cart()->count());
        cart()->add($product);
        $this->assertEquals(1, cart()->count());

        $this->post(route('cart.quantity', $product), ['quantity' => 4])
            ->assertStatus(302)
            ->assertRedirect();
        $this->assertEquals(4, cart()->count());
    }
    public function it_delete():void
    {
        $product = ProductFactory::new()->create();
        $this->assertEquals(0, cart()->count());
        cart()->add($product);
        $this->assertEquals(1, cart()->count());

        $this->delete(route('cart.delete', $product))
            ->assertStatus(302)
            ->assertRedirect();
        $this->assertEquals(0, cart()->count());
    }
    public function it_truncate():void
    {
        $product = ProductFactory::new()->create();
        $this->assertEquals(0, cart()->count());
        cart()->add($product);
        $this->assertEquals(1, cart()->count());

        $this->delete(route('cart.truncate'))
            ->assertStatus(302)
            ->assertRedirect();
        $this->assertEquals(0, cart()->count());
    }
}
