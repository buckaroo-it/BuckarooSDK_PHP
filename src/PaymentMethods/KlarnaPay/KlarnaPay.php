<?php

namespace Buckaroo\PaymentMethods\KlarnaPay;

use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\PaymentMethods\Traits\HasArticleAndCustomerParameters;

class KlarnaPay extends PaymentMethod
{
    use HasArticleAndCustomerParameters;

    protected string $paymentName = 'klarna';
}