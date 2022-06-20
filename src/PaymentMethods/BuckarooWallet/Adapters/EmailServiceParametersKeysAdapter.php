<?php

namespace Buckaroo\PaymentMethods\BuckarooWallet\Adapters;

class EmailServiceParametersKeysAdapter extends CustomerServiceParametersKeysAdapter
{
    protected array $keys = [
        'email'    => 'ConsumerEmail'
    ];
}