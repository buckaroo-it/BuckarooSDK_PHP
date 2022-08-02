<?php

namespace Buckaroo\PaymentMethods\KlarnaPay\Models;

class PayPayload extends \Buckaroo\Models\Payload\PayPayload
{
    protected string $servicesSelectableByClient;
    protected string $servicesExcludedForClient;
    protected string $originalTransactionReference;
}