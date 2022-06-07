<?php

namespace Buckaroo\Model\Adapters\ServiceParametersKeys;

class BankTransferCustomerAdapter extends Adapter
{
    protected array $keys = [
        'gender' => 'CustomerGender',
        'firstName' => 'CustomerFirstName',
        'lastName' => 'CustomerLastName',
        'email' => 'CustomerEmail',
        'country' => 'CustomerCountry'
    ];
}