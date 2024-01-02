<?php

namespace Domain\Order\Payment;

use Closure;
use Domain\Order\Contracts\PaymentGatewayContract;
use Domain\Order\Exceptions\PaymentProcessException;
use Domain\Order\Exceptions\PaymentProviderException;
use Domain\Order\Models\Payment;
use Domain\Order\Models\PaymentHistory;
use Domain\Order\States\Payment\CanceledPaymentState;
use Domain\Order\States\Payment\PaidPaymentState;
use Domain\Order\Traits\PaymentEvents;

class PaymentSystem
{
    use PaymentEvents;
    protected static PaymentGatewayContract $provider;

    /**
     * @throws PaymentProviderException
     */
    public static function provider(PaymentGatewayContract|Closure $providerOrClosure):void
    {
        if(is_callable($providerOrClosure)){
            $providerOrClosure = call_user_func($providerOrClosure);
        }
        if(!$providerOrClosure instanceof PaymentGatewayContract){
            throw PaymentProviderException::providerRequired();
        }
        self::$provider = $providerOrClosure;
    }

    /**
     * @throws PaymentProviderException
     */
    public static function create(PaymentData $paymentData): PaymentGatewayContract
    {
        if(!self::$provider instanceof PaymentGatewayContract){
            throw PaymentProviderException::providerRequired();
        }
        Payment::query()->create([
            'payment_id' => $paymentData->paymentId,
            'payment_gateway' => $paymentData->paymentGateway,
            'amount' => $paymentData->amount,
            'meta' => $paymentData->meta,
        ]);
        if(is_callable(self::$onCreating)){
            $paymentData = call_user_func(self::$onCreating, $paymentData);
        }
        return self::$provider->data($paymentData);
    }

    /**
     * @throws PaymentProviderException
     */
    public static function validate(): PaymentGatewayContract
    {
        if(!self::$provider instanceof PaymentGatewayContract){
            throw PaymentProviderException::providerRequired();
        }

        PaymentHistory::query()->create([
            'method' => request()->method(),
            'payment_gateway' => get_class(self::$provider),
            'payload' => self::$provider->request(),
        ]);

        if(is_callable(self::$onValidating)){
            self::$provider = call_user_func(self::$onValidating);
        }

        if(self::validate() && self::$provider->paid()){
            try {
                $payment = Payment::query()
                    ->where('payment_id', self::$provider->paymentId())
                    ->firstOr(function (){
                        throw PaymentProcessException::paymentNotFound();
                    });
                if(is_callable(self::$onSuccess)){
                    $payment = call_user_func(self::$onSuccess, $payment);
                }
                $payment->state->transitionTo(PaidPaymentState::class);
            }
            catch (PaymentProcessException $exception) {
                Payment::query()->find(self::$provider->paymentId())->state->transitionTo(CanceledPaymentState::class);
//                $payment->state->transitionTo(CanceledPaymentState::class);

                if(is_callable(self::$onError)){
                    $payment = call_user_func(
                        self::$onError,
                        self::$provider->errorMessage() ?? $exception->getMessage()
                );
                }
            }
        }
        return self::$provider;
    }
}
