<?php

namespace Buckaroo\PaymentMethods;

interface PaymentInterface
{
    public function paymentName(): string;
    public function serviceVersion(): int;
}