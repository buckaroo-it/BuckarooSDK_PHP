<?php
declare(strict_types=1);

namespace Buckaroo\Model;

class PaymentPayload extends Payload
{
    protected string $order;
    protected float $amountDebit;

    public function __construct(?array $payload)
    {
        $this->order = uniqid($_ENV['BPE_WEBSITE'] . '_ORDER_NO_');

        parent::__construct($payload);
    }
}