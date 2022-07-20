<?php

namespace Buckaroo\Services\TransactionHeaders;

abstract class TransactionHeader
{
    protected TransactionHeader $transactionHeader;

    protected array $headers = [];

    public function __construct(TransactionHeader $transactionHeader) {
        $this->transactionHeader = $transactionHeader;
    }

    public function getHeaders(): array {
        return $this->headers;
    }
}