<?php

namespace Buckaroo\PaymentMethods\PayPerEmail\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class AttachmentServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'name'            => 'attachment'
    ];
}