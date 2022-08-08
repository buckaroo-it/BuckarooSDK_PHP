<?php

namespace Buckaroo\PaymentMethods\KlarnaKP\Service\ParameterKeys;

class AddressAdapter extends RecipientAdapter
{
    protected array $keys = [
        'zipcode'                   => 'PostalCode',
        'houseNumberAdditional'     => 'HouseNumberSuffix'
    ];
}