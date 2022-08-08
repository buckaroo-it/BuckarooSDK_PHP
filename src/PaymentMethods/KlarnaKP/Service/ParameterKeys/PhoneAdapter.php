<?php

namespace Buckaroo\PaymentMethods\KlarnaKP\Service\ParameterKeys;

class PhoneAdapter extends RecipientAdapter
{
    protected array $keys = [
        'landLine'      => 'PhoneNumber',
        'mobile'        => 'CellPhoneNumber'
    ];
}