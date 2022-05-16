<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Payload\TransactionRequest;
use Buckaroo\Payload\TransactionResponse;

interface PaymentInterface
{
    public function getCode(): string;
    public function pay(TransactionRequest $request) : TransactionResponse;
    public function refund(TransactionRequest $request) : TransactionResponse;
}