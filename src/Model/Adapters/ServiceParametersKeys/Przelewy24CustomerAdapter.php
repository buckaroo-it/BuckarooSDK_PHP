<?php

namespace Buckaroo\Model\Adapters\ServiceParametersKeys;

class Przelewy24CustomerAdapter extends Adapter
{
    protected array $keys = [
        'firstName'        => 'CustomerFirstName',
        'lastName'        => 'CustomerLastName',
        'email'        => 'CustomerEmail',
    ];
}