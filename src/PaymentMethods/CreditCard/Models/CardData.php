<?php

namespace Buckaroo\PaymentMethods\CreditCard\Models;

use Buckaroo\Models\ServiceParameter;

class CardData extends ServiceParameter
{
    protected string $encryptedCardData;
}