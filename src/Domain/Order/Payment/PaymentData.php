<?php

namespace Domain\Order\Payment;

use Illuminate\Support\Collection;
use Support\ValueObjects\Number;

class PaymentData
{

    public function __construct(
        public string $paymentId,
        public string $description,
        public string $returnUrl,
        public Number $amount,
        public Collection $meta,
        public string $paymentGateway,
    )
    {
    }
}
