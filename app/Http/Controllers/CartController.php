<?php

namespace App\Http\Controllers;

use Domain\Cart\Models\CartItem;
use Domain\Product\Models\Product;
use Domain\Product\Collections\ProductCollection;
use Domain\Product\ViewModels\ProductViewModel;
use Illuminate\Http\RedirectResponse;


class CartController extends Controller
{
    public function index()
    {
        return view('cart.index', [
            'items' => cart()->items(),
        ]);
    }

    public function add(Product $product): RedirectResponse
    {
        cart()->add(
            product: $product,
            quantity: request('quantity', 1),
            optionValues: request('options', [])
        );

        flash()->info('Товар добавлен в корзину');
        return redirect()
            ->back();
    }

    public function quantity(CartItem $cartItem): RedirectResponse
    {
        cart()->quantity(
            cartItem: $cartItem,
            quantity: request('quantity', 1)
        );

        flash()->info('Количество изменено');
        return redirect()
            ->back();
    }
    public function delete(CartItem $cartItem): RedirectResponse
    {
        cart()->delete(
            cartItem: $cartItem
        );

        flash()->info('Товар удален из корзины');
        return redirect()
            ->back();
    }

    public function truncate()
    {
        cart()->truncate();

        flash()->info('Корзина очищена');
        return redirect()
            ->back();
    }
}
