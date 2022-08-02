<?php

namespace Buckaroo\PaymentMethods\In3\Models;

use Buckaroo\Models\ClientIP;

class PayPayload extends \Buckaroo\Models\Payload\PayPayload
{
    protected ClientIP $clientIP;

    public function __construct(?array $payload)
    {
        $this->clientIP = new ClientIP();

        parent::__construct($payload);
    }
}