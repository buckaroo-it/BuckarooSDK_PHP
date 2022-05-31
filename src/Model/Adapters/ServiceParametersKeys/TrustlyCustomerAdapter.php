<?php

namespace Buckaroo\Model\Adapters\ServiceParametersKeys;

class TrustlyCustomerAdapter extends Adapter
{
    protected array $keys = [
        'firstName'        => 'CustomerFirstName',
        'lastName'          => 'CustomerLastName',
        'country'           => 'CustomerCountryCode'
    ];
}