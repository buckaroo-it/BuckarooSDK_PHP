<?php

namespace Buckaroo\Models\Adapters\ServiceParametersKeys;

class TrustlyCustomerAdapter extends Adapter
{
    protected array $keys = [
        'firstName'        => 'CustomerFirstName',
        'lastName'          => 'CustomerLastName',
        'country'           => 'CustomerCountryCode'
    ];
}