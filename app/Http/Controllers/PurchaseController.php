<?php

namespace App\Http\Controllers;

use Domain\Order\Exceptions\PaymentProviderException;
use Domain\Order\Payment\PaymentData;
use Domain\Order\Payment\PaymentSystem;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class PurchaseController extends Controller
{
    /**
     * @throws PaymentProviderException
     */
    public function index(): Redirector|Application|RedirectResponse
    {
        $redirect = PaymentSystem::create(new PaymentData([

        ]) )->url();

        return redirect($redirect);
    }

    /**
     * @throws PaymentProviderException
     */
    public function callback(): JsonResponse
    {
        return PaymentSystem::validate()
            ->response();
    }
}
