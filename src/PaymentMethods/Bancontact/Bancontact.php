<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\Bancontact;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\Bancontact\Models\Authenticate;
use Buckaroo\PaymentMethods\Bancontact\Models\Pay;
use Buckaroo\PaymentMethods\Bancontact\Models\PayEncrypted;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class Bancontact extends PayablePaymentMethod
{
    protected string $paymentName = 'bancontactmrcash';
    protected int $serviceVersion = 0;

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay($model ?? new Pay($this->payload));
    }

    public function payEncrypted(): TransactionResponse
    {
        $payEncrypted = new PayEncrypted($this->payload);

        $this->setPayPayload();

        $this->setServiceList('PayEncrypted', $payEncrypted);

        return $this->postRequest();
    }

    public function payRecurrent(): TransactionResponse
    {
        $this->setPayPayload();

        $this->setServiceList('PayRecurrent');

        return $this->postRequest();
    }

    public function authenticate(): TransactionResponse
    {
        $authenticate = new Authenticate($this->payload);

        $this->setPayPayload();

        $this->setServiceList('Authenticate', $authenticate);

        return $this->postRequest();
    }
}