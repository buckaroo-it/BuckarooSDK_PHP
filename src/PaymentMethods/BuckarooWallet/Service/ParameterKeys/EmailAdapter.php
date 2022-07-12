<?php

namespace Buckaroo\PaymentMethods\BuckarooWallet\Service\ParameterKeys;

class EmailAdapter extends CustomerAdapter
{
    protected array $keys = [
        'email'    => 'ConsumerEmail'
    ];
}