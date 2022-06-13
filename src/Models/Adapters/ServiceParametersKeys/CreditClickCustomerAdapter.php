<?php

namespace Buckaroo\Models\Adapters\ServiceParametersKeys;

class CreditClickCustomerAdapter extends Adapter
{
    protected array $keys = [
        'firstName'        => 'firstname',
        'lastName'        => 'lastname',
        'email'        => 'email',
    ];
}