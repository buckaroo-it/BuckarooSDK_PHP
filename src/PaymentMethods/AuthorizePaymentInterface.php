<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Payload\TransactionRequest;

interface AuthorizePaymentInterface extends PaymentInterface
{
    public function authorize(TransactionRequest $request);
    public function capture(TransactionRequest $request);
}