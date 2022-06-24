<?php

namespace Buckaroo\PaymentMethods\BuckarooWallet\Service\ParameterKeys;

class BankAccountAdapter extends CustomerAdapter
{
    protected array $keys = [
        'iban'    => 'ConsumerIban'
    ];
}