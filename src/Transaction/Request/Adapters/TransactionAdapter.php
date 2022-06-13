<?php

namespace Buckaroo\Transaction\Request\Adapters;

use Buckaroo\Models\Payload;

abstract class TransactionAdapter
{
    protected Payload $payload;
    protected array $keys = [];

    public function __construct(Payload $payload)
    {
        $this->payload = $payload;
    }

    public function getValues(): array
    {
        $values = array();

        foreach(array_filter($this->payload->toArray()) as $key => $value)
        {
            $keyName = (isset($this->keys[$key]))? $this->keys[$key] : ucfirst($key);

            $values[$keyName] = $value;
        }

        return $values;
    }
}