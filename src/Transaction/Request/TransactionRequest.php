<?php

namespace Buckaroo\Transaction\Request;

use Buckaroo\Models\Services;
use Buckaroo\Resources\Arrayable;

class TransactionRequest extends Request
{
    public function __construct()
    {
        $this->data['ClientUserAgent'] =  $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    public function setPayload(array $payload)
    {
        foreach($payload as $property => $value)
        {
            $this->data[$property] = $value;
        }

        return $this;
    }

    public function getServices() : Services
    {
        $this->data['Services'] = $this->data['Services'] ?? new Services;

        return $this->data['Services'];
    }

    public function toArray(): array
    {
        foreach($this->data as $key => $value)
        {
            if(is_a($value, Arrayable::class))
            {
                $this->data[$key] = $value->toArray();
            }
        }

        return $this->data;
    }
}
