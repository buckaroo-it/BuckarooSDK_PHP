<?php

namespace Buckaroo\PaymentMethods\Alipay\Models;

use Buckaroo\Models\ServiceParameter;

class Pay extends ServiceParameter
{
    protected bool $useMobileView;
}