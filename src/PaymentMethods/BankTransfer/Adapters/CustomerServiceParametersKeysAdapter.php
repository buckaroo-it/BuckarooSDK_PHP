<?php

namespace Buckaroo\PaymentMethods\BankTransfer\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class CustomerServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'gender'        => 'CustomerGender',
        'firstName'     => 'CustomerFirstName',
        'lastName'      => 'CustomerLastName',
        'email'         => 'CustomerEmail',
    ];
}