<?php

namespace Buckaroo\PaymentMethods\iDealQR;

use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\iDealQR\Models\Generate;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;
use Buckaroo\Services\ServiceListParameters\iDealQRParameters;
use Buckaroo\Transaction\Response\TransactionResponse;

class iDealQR extends PaymentMethod
{
    protected string $paymentName = 'idealqr';

    public function generate()
    {
        $generate = new Generate($this->payload);

        $this->setServiceList('Generate', $generate);

        return $this->dataRequest();
    }
}