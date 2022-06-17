<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\SEPA;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\PaymentMethods\SEPA\Adapters\PayServiceParametersKeysAdapter;
use Buckaroo\PaymentMethods\SEPA\Models\ExtraInfo;
use Buckaroo\PaymentMethods\SEPA\Models\Pay;
use Buckaroo\Transaction\Response\TransactionResponse;

class SEPA extends PaymentMethod
{
    protected string $paymentName = 'SepaDirectDebit';
    protected int $serviceVersion = 1;

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new PayServiceParametersKeysAdapter(new Pay($this->payload)));
    }

    public function authorize(): TransactionResponse
    {
        $pay = new PayServiceParametersKeysAdapter(new Pay($this->payload));

        $this->setPayPayload();

        $this->setServiceList('Authorize', $pay);

        return $this->postRequest();
    }

    public function payRecurrent()
    {
        $pay = new PayServiceParametersKeysAdapter(new Pay($this->payload));

        $this->setPayPayload();

        $this->setServiceList('PayRecurrent', $pay);

        return $this->postRequest();
    }

    public function extraInfo()
    {
        $extraInfo = new PayServiceParametersKeysAdapter(new ExtraInfo($this->payload));

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
