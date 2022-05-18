<?php

namespace Buckaroo\Transaction\Request\Adapters;

use Buckaroo\Model\Payload;

abstract class TransactionAdapter
{
    abstract public function getValues();

    protected $payload;

    public function __construct(Payload $payload)
    {
        $this->payload = $payload;
    }
}