<?php

namespace Buckaroo\PaymentMethods\BankTransfer\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class CustomerAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'gender'        => 'CustomerGender',
        'firstName'     => 'CustomerFirstName',
        'lastName'      => 'CustomerLastName',
        'email'         => 'CustomerEmail',
    ];
}