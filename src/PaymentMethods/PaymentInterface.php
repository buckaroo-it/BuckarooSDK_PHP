<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\Payload;
use Buckaroo\Model\ServiceList;
use Buckaroo\Transaction\Request\TransactionRequest;
use Buckaroo\Transaction\Response\TransactionResponse;

interface PaymentInterface
{
    public function getCode(): string;
    public function pay(TransactionRequest $request) : TransactionResponse;
    public function refund(TransactionRequest $request) : TransactionResponse;

    public function getPayServiceList(Payload $payload) : ServiceList;
    public function getRefundServiceList(Payload $payload) : ServiceList;
}