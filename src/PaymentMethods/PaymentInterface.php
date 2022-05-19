<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\RefundPayload;
use Buckaroo\Model\ServiceList;
use Buckaroo\Transaction\Request\TransactionRequest;
use Buckaroo\Transaction\Response\TransactionResponse;

interface PaymentInterface
{
    public function getCode(): string;
    public function pay(TransactionRequest $request) : TransactionResponse;
    public function refund(TransactionRequest $request) : TransactionResponse;

    public function getPayServiceList(PaymentPayload $payload, array $serviceParameters = []) : ServiceList;
    public function getRefundServiceList(RefundPayload $payload) : ServiceList;
}