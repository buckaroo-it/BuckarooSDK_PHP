<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Handlers\Reply\ReplyHandler;
use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\RefundPayload;
use Buckaroo\Model\ServiceList;
use Buckaroo\Transaction\Request\TransactionRequest;
use Buckaroo\Transaction\Response\TransactionResponse;

interface PaymentInterface
{
    public function pay($request) : TransactionResponse;
    public function refund($request) : TransactionResponse;

    public function paymentName(): string;
    public function serviceVersion(): int;

    public function setPayServiceList(array $serviceParameters = []);
    public function setRefundServiceList();

    public function getPaymentPayload(): array;
    public function getRefundPayload(): array;

    public function handleReply(array $data): ReplyHandler;
}