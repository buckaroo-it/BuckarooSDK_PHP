<?php

namespace Buckaroo\PaymentMethods\PayPerEmail\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class AttachmentAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'name'            => 'attachment'
    ];
}