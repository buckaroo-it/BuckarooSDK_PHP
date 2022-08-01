<?php

namespace Buckaroo\Services\TransactionHeaders;

class DefaultHeader extends TransactionHeader
{
    public function __construct(?array $headers = null)
    {
        $this->headers = $headers ?? [];
    }
}