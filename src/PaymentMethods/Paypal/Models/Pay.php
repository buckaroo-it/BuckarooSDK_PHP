<?php

namespace Buckaroo\PaymentMethods\Paypal\Models;

use Buckaroo\Models\ServiceParameter;

class Pay extends ServiceParameter
{
    protected string $buyerEmail;
    protected string $productName;
    protected string $billingAgreementDescription;
    protected string $pageStyle;
    protected string $startrecurrent;
    protected string $payPalOrderId;
}