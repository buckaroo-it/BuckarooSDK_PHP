<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\ServiceList;
use Buckaroo\Services\PayloadService;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;
use Buckaroo\Services\ServiceListParameters\iDealQRParameters;
use Buckaroo\Transaction\Response\TransactionResponse;

class IdealQR extends PaymentMethod
{
    public const SERVICE_VERSION = 0;
    public const PAYMENT_NAME = 'idealqr';

    public function generate($payload)
    {
        $this->payload = (new PayloadService($payload))->toArray();

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

    public function paymentName(): string
    {
        return self::PAYMENT_NAME;
    }

    public function serviceVersion(): int
    {
        return self::SERVICE_VERSION;
    }
}