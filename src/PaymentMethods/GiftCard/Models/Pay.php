<?php

namespace Buckaroo\PaymentMethods\GiftCard\Models;

use Buckaroo\Models\ServiceParameter;

class Pay extends ServiceParameter
{
    protected string $fashionChequeCardNumber;
    protected string $intersolveCardnumber;
    protected string $intersolvePIN;
    protected string $tcsCardnumber;
    protected string $tcsValidationCode;
    protected string $lastName;
    protected string $email;
}