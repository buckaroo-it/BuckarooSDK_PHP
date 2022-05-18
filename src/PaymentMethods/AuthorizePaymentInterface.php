<?php

namespace Buckaroo\PaymentMethods;


use Buckaroo\Transaction\Request\TransactionRequest;

interface AuthorizePaymentInterface extends PaymentInterface
{
    public function authorize(TransactionRequest $request);
    public function capture(TransactionRequest $request);
}