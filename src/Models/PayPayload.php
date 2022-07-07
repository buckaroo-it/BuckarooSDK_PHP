<?php
declare(strict_types=1);

namespace Buckaroo\Models;

class PayPayload extends Payload
{
    protected string $order;
    protected float $amountDebit;

    public function __construct(?array $payload)
    {
        $this->order = uniqid('ORDER_NO_');

        parent::__construct($payload);
    }
}