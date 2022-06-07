<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\ServiceList;
use Buckaroo\Services\PayloadService;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;
use Buckaroo\Services\ServiceListParameters\iDealQRParameters;
use Buckaroo\Transaction\Response\TransactionResponse;

class IdealQR extends PaymentMethod
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