<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\PaymentMethods\Traits\HasArticleAndCustomerParameters;

class KlarnaPay extends PaymentMethod
{
    use HasArticleAndCustomerParameters;

    protected string $paymentName = 'klarna';
}