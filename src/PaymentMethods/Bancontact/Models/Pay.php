<?php

namespace Buckaroo\PaymentMethods\Bancontact\Models;

use Buckaroo\Models\ServiceParameter;

class Pay extends ServiceParameter
{
    protected bool $saveToken;
}