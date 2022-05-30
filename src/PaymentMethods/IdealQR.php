<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\ServiceList;
use Buckaroo\Services\PayloadService;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;
use Buckaroo\Services\ServiceListParameters\iDealQRParameters;

class IdealQR extends PaymentMethod
{
    public const SERVICE_VERSION = 0;
    public const PAYMENT_NAME = 'IdealQr';

    public function generate($payload)
    {
        $this->payload = (new PayloadService($payload))->toArray();
        $this->request->setPayload($this->getPaymentPayload());

        $serviceList =  new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Generate'
        );

        $parametersService = new DefaultParameters($serviceList);
        $parametersService = new iDealQRParameters($parametersService, $this->payload['serviceParameters']['ideal_qr'] ?? []);
        $parametersService->data();

        $this->request->getServices()->pushServiceList($serviceList);

        return $this->postRequest();
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