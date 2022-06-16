<?php

namespace Buckaroo\PaymentMethods\Billink;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\Billink\Models\Capture;
use Buckaroo\PaymentMethods\Billink\Models\Pay;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\PaymentMethods\Traits\HasArticleAndCustomerParameters;
use Buckaroo\Transaction\Response\TransactionResponse;

class Billink extends PaymentMethod
{
    use HasArticleAndCustomerParameters;

    protected string $paymentName = 'Billink';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }

    public function authorize(): TransactionResponse
    {
        $pay = new Pay($this->payload);

        $this->setPayPayload();

        $this->setServiceList('Authorize', $pay);

        return $this->postRequest();
    }

    public function capture(): TransactionResponse
    {
        $capture = new Capture($this->payload);

        $this->setPayPayload();

        $this->setServiceList('Capture', $capture);

        return $this->postRequest();
    }
}