<?php

namespace Domain\Order\States;

use Domain\Order\Enums\OrderStatuses;
use Domain\Order\Events\OrderStatusChangedEvent;
use Domain\Order\Models\Order;
use http\Exception\InvalidArgumentException;

abstract class OrderState
{
    protected array $allowedTransitions = [
        OrderStatuses::class,
    ];
    public function __construct(
        protected Order $order
    )
    {
    }

    abstract public function canBeChanged(): bool;
    abstract public function value(): string;
    abstract public function humanValue(): string;
    public function transitionTo( OrderState $state): void
    {
        if(!$this->canBeChanged())
            throw new InvalidArgumentException('Состояние не может быть изменено');
        if(!in_array(get_class($state), $this->allowedTransitions))
            throw new InvalidArgumentException("Невозможно изменить статус для {$this->order->status->value()} на {$state->value()}");


        $this->order->updateQuietly([
            'status' => $state->value(),
        ]);

        event( new OrderStatusChangedEvent($this->order, $this->order->status, $state));

    }

}
