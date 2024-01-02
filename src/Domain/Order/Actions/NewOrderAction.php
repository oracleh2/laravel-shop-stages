<?php

namespace Domain\Order\Actions;

use App\Http\Requests\OrderFormRequest;
use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Domain\Order\Models\Order;
use Support\SessionRegenerator;

class NewOrderAction
{
    public function __invoke(OrderFormRequest $request): Order
    {
        $registerAction = app(RegisterNewUserContract::class);
        $customer = $request->get('customer');
        if($request->boolean('create_account')) {
            $user = $registerAction(
                NewUserDTO::make(
                    $customer['first_name'] . ' ' . $customer['last_name'],
                    $customer['email'],
                    $request->get('password'),
                )
            );
            SessionRegenerator::run(fn() => auth()->login($user));
        }
        return Order::query()->create([
            'user_id' => auth()->check() ? auth()->user()->id : null,
            'payment_method_id' => $request->get('payment_method_id'),
            'delivery_type_id' => $request->get('delivery_type_id'),
        ]);
    }
}
