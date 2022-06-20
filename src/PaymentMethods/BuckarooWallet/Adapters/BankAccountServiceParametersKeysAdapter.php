<?php

namespace Buckaroo\PaymentMethods\BuckarooWallet\Adapters;

class BankAccountServiceParametersKeysAdapter extends CustomerServiceParametersKeysAdapter
{
    protected array $keys = [
        'iban'    => 'ConsumerIban'
    ];
}