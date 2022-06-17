<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Handlers\Reply\ReplyHandler;
use Buckaroo\Models\Model;

interface PaymentInterface
{
    public function pay(?Model $model = null);
    public function refund(?Model $model = null);

    public function paymentName(): string;
    public function serviceVersion(): int;

    public function handleReply(array $data): ReplyHandler;
}