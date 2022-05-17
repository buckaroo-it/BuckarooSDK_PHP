<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\TransactionRequest;
use Buckaroo\Payload\TransactionResponse;
use http\Exception;

interface PaymentInterface
{
    public function getCode(): string;
    public function pay(TransactionRequest $request) : TransactionResponse;
    public function refund(TransactionRequest $request) : TransactionResponse;
}