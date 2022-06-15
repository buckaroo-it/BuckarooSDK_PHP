<?php

namespace Buckaroo\PaymentMethods\AfterpayDigiAccept\Adapters;

class PhoneServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'mobile'     => 'PhoneNumber'
    ];
}