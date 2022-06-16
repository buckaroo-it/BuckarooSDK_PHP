<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Handlers\Reply\ReplyHandler;
use Buckaroo\Models\Model;
use Buckaroo\Transaction\Response\TransactionResponse;

interface PaymentInterface
{
    public function pay(?Model $model = null) : TransactionResponse;
    public function refund(?Model $model = null) : TransactionResponse;

    public function paymentName(): string;
    public function serviceVersion(): int;

    public function handleReply(array $data): ReplyHandler;
}