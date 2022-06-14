<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Handlers\Reply\ReplyHandler;
use Buckaroo\Models\Model;
use Buckaroo\Transaction\Response\TransactionResponse;

interface PaymentInterface
{
    public function pay(?Model $model = null) : TransactionResponse;
    public function refund() : TransactionResponse;

    public function paymentName(): string;
    public function serviceVersion(): int;

//    public function setPayServiceList(array $serviceParameters = []);
//    public function setRefundServiceList();

//    public function getPaymentPayload(): array;
//    public function getRefundPayload(): array;

    public function handleReply(array $data): ReplyHandler;
}