<?php

namespace Buckaroo\PaymentMethods\Bancontact\Models;

use Buckaroo\Models\ServiceParameter;

class PayEncrypted extends ServiceParameter
{
    protected string $encryptedCardData;
}