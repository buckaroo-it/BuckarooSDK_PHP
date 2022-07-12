<?php

namespace Buckaroo\PaymentMethods\Bancontact\Models;

use Buckaroo\Models\ServiceParameter;

class Authenticate extends ServiceParameter
{
    protected bool $saveToken;
}