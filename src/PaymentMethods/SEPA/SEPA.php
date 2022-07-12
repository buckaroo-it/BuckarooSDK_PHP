<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\SEPA;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\PaymentMethods\SEPA\Models\ExtraInfo;
use Buckaroo\PaymentMethods\SEPA\Models\Pay;
use Buckaroo\PaymentMethods\SEPA\Service\ParameterKeys\PayAdapter;
use Buckaroo\Transaction\Response\TransactionResponse;

class SEPA extends PayablePaymentMethod
{
    protected string $paymentName = 'SepaDirectDebit';
    protected int $serviceVersion = 1;

    public function pay(?Model $model = null)
    {
        return parent::pay($model ?? new PayAdapter(new Pay($this->payload)));
    }

    public function authorize(): TransactionResponse
    {
        $pay = new PayAdapter(new Pay($this->payload));

        $this->setPayPayload();

        $this->setServiceList('Authorize', $pay);

        return $this->postRequest();
    }

    public function payRecurrent()
    {
        $pay = new PayAdapter(new Pay($this->payload));

        $this->setPayPayload();

        $this->setServiceList('PayRecurrent', $pay);

        return $this->postRequest();
    }

    public function extraInfo()
    {
        $extraInfo = new PayAdapter(new ExtraInfo($this->payload));

        $this->setPayPayload();

        $this->setServiceList('Pay,ExtraInfo', $extraInfo);

        return $this->postRequest();
    }

    public function payWithEmandate()
    {
        $pay = new Pay($this->payload);

        $this->setPayPayload();

        $this->setServiceList('PayWithEmandate', $pay);

        return $this->postRequest();
    }
}
