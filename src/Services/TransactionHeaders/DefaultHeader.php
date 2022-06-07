<?php

namespace Buckaroo\Services\TransactionHeaders;

use Buckaroo\Model\ServiceList;

class DefaultHeader extends TransactionHeader
{
    public function __construct(?array $headers = null)
    {
        $this->headers = $headers ?? [];
    }
}