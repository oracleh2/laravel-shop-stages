<?php

namespace Domain\Order\Providers;

use Domain\Cart\CartManager;
use Domain\Cart\Contracts\CartIdentityStorageContract;
use Domain\Cart\StorageIdentities\SessionIdentityStorage;
use Domain\Order\Models\Payment;
use Domain\Order\Payment\PaymentData;
use Domain\Order\Payment\PaymentSystem;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
//        PaymentSystem::provider();
        PaymentSystem::onCreating(function (PaymentData $paymentData) {
            return $paymentData;
        });
        PaymentSystem::OnSuccess(function (Payment $payment) {

        });
        PaymentSystem::OnError(function (string $message, Payment $payment) {

        });
    }
    public function register(): void
    {

    }
}
