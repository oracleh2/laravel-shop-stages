<?php

namespace Domain\Cart;

use Domain\Cart\Contracts\CartIdentityStorageContract;
use Domain\Cart\Models\Cart;
use Domain\Cart\Models\CartItem;
use Domain\Cart\StorageIdentities\FakeSessionIdentityStorage;
use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Support\ValueObjects\Number;


class CartManager
{
    protected ?Number $sum;
    public function __construct(
        protected CartIdentityStorageContract $identityStorage
    )
    {
//        dd(Number::make(10)->mul(2));
    }
    public static function fake()
    {
        app()->bind(CartIdentityStorageContract::class, FakeSessionIdentityStorage::class);
    }
    private function cacheKey(): string
    {
        return str('cart_' . $this->identityStorage->get())
            ->slug('_')
            ->value();
    }
    private function forgetCache(): void
    {
        Cache::forget($this->cacheKey());
    }
    private function storedData(string $id): array
    {
        $data = [
            'storage_id' => $id,
        ];
        if(auth()->check()) {
            $data['user_id'] = auth()->id();
        }

        return $data;
    }
    private function stringedOptionValues(array $optionValues = []): string
    {
        sort($optionValues);
        return implode(';', $optionValues);
    }
    public function add(Product $product, int $quantity = 1, array $optionValues = []): Model|Builder
    {

        $cart = Cart::query()
            ->updateOrCreate([
                'storage_id' => $this->identityStorage->get(),
            ],
                $this->storedData($this->identityStorage->get()),
            );


        $cartItem = $cart
            ->cartItems()
            ->where('product_id', $product->getKey())
            ->where('string_option_values', $this->stringedOptionValues($optionValues))
            ->first();

        if($cartItem) {
            $cartItem->increment('quantity', $quantity);

        }
        else{
//            dd($this->stringedOptionValues($optionValues));
            $cartItem = $cart
                ->cartItems()
                ->create([
                    'product_id' => $product->getKey(),
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'string_option_values' => $this->stringedOptionValues($optionValues),
                ]);
        }
//        $cartItem = $cart
//            ->cartItems()
//            ->updateOrCreate([
//                'product_id' => $product->getKey(),
//                'string_option_values' => $this->stringedOptionValues($optionValues),
//            ],[
//                'price' => $product->price,
//                'quantity' => DB::raw("quantity + $quantity"),
//                'string_option_values' => $this->stringedOptionValues($optionValues),
//            ])
////            ->increment('quantity', $quantity)
//        ;

        $cartItem->optionValues()->sync($optionValues);
        $this->forgetCache();
        return $cart;
    }
    public function quantity(CartItem $cartItem, int $quantity = 1): void
    {
        $cartItem->update([
            'quantity' =>  $quantity,
        ]);
        $this->forgetCache();
    }
    public function delete(CartItem $cartItem): void
    {
        $cartItem->delete();
        $this->forgetCache();
    }
    public function truncate(): void
    {
        if($this->get()) {
            $this->get()->delete();
        }

        $this->forgetCache();
    }
    public function get()
    {
        return Cache::remember($this->cacheKey(), now()->addHour(), function () {
                return Cart::query()
                    ->with('cartItems')
                    ->where('storage_id', $this->identityStorage->get())
                    ->when(auth()->check(), fn (Builder $query) => $query->orWhere('user_id', auth()->id()))
                    ->first() ?? false;
            });
    }
    public function cartItems(): Collection
    {
        if(!$this->get()) {
            return new Collection();
        }

        return $this->get()->cartItems;
    }
    public function items(): Collection
    {
        if(!$this->get()) {
            return new Collection();
        }
        return CartItem::query()
            ->with(['product', 'optionValues.option'])
            ->whereBelongsTo($this->get())
            ->get();
    }

    public function count(): int
    {
        return $this->cartItems()->sum(function ($item) {
            return $item->quantity;
        });
    }
    public function totalAmount()
    {
        $this->sum = Number::make(0);
        $this->cartItems()->sum(function ($item){
            $this->sum = $this->sum->add($item->amount);
        });
        return $this->sum;
    }
    public function updateStorageId(string $old, string $new): void
    {
        Cart::query()
            ->where('storage_id', $old)
            ->update($this->storedData($new));
    }
}
