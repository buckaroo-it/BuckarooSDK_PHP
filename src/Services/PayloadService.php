<?php

namespace Buckaroo\Services;

use Buckaroo\Helpers\Arrayable;

class PayloadService implements Arrayable
{
    private array $payload;

    public function __construct($payload) {
        $this->setPayload($payload);
    }

    protected function setPayload($payload)
    {
        if (is_array($payload))
        {
            $this->payload = $payload;

            return $this;
        }

        if(is_string($payload)) {
            $this->payload = json_decode($payload, true);
        }

        if($this->payload == null) {
            throw new \Exception("Invalid or empty payload. Array or json format required.");
        }

        return $this;
    }

    public function toArray(): array
    {
        return $this->payload;
    }
}