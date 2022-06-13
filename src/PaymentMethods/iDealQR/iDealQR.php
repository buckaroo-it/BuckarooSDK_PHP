<?php

namespace Buckaroo\PaymentMethods\iDealQR;

use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;
use Buckaroo\Services\ServiceListParameters\iDealQRParameters;
use Buckaroo\Transaction\Response\TransactionResponse;

class iDealQR extends PaymentMethod
{
    protected string $paymentName = 'idealqr';

    public function generate()
    {
        $serviceList =  new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Generate'
        );

        $parametersService = new iDealQRParameters(new DefaultParameters($serviceList), $this->payload['serviceParameters']['ideal_qr'] ?? []);
        $parametersService->data();

        $this->request->getServices()->pushServiceList($serviceList);

        return $this->client->dataRequest(
            $this->request,
            TransactionResponse::class
        );
    }
}