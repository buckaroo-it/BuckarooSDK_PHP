<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Handlers\Reply\ReplyHandler;

interface PaymentInterface
{
    public function paymentName(): string;
    public function serviceVersion(): int;

    public function handleReply(array $data): ReplyHandler;
}