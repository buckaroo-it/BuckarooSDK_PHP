<?php

namespace Buckaroo\PaymentMethods\Afterpay\Models;

class Person extends \Buckaroo\Models\Person
{
    protected string $customerNumber;
    protected string $identificationNumber;
    protected string $conversationLanguage;
}