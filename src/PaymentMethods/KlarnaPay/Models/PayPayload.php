<?php

namespace Buckaroo\PaymentMethods\KlarnaPay\Models;

class PayPayload extends \Buckaroo\Models\PayPayload
{
    protected string $servicesSelectableByClient;
    protected string $servicesExcludedForClient;
    protected string $originalTransactionReference;
}