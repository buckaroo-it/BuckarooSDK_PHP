<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\CapturePayload;
use Buckaroo\Model\ServiceList;
use Buckaroo\Services\PayloadService;
use Buckaroo\Services\ServiceListParameters\ArticleParameters;
use Buckaroo\Services\ServiceListParameters\CustomerParameters;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;
use Buckaroo\Transaction\Request\Adapters\CapturePayloadAdapter;
use Buckaroo\Transaction\Response\TransactionResponse;

class Billink extends PaymentMethod
{
    public const SERVICE_VERSION = 0;
    public const PAYMENT_NAME = 'billink';

    public function setPayServiceList(array $serviceParameters = [])
    {
        $serviceList =  new ServiceList(
            self::PAYMENT_NAME,
            self::SERVICE_VERSION,
            'Pay'
        );

        $parametersService = new DefaultParameters($serviceList);
        $parametersService = new ArticleParameters($parametersService, $serviceParameters['articles'] ?? []);
        $parametersService = new CustomerParameters($parametersService, $serviceParameters['customer'] ?? []);
        $parametersService->data();

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }

    public function authorize($payload): TransactionResponse
    {
        $this->payload = (new PayloadService($payload))->toArray();
        $this->request->setPayload($this->getPaymentPayload());

        $serviceList = new ServiceList(
            self::PAYMENT_NAME,
            self::SERVICE_VERSION,
            'Authorize'
        );

        $parametersService = new DefaultParameters($serviceList);
        $parametersService = new ArticleParameters($parametersService, $this->payload['serviceParameters']['articles'] ?? []);
        $parametersService = new CustomerParameters($parametersService, $this->payload['serviceParameters']['customer'] ?? []);
        $parametersService->data();

        $serviceList->appendParameter([
            [
                "Name"              => "Trackandtrace",
                "Value"             => $this->payload['serviceParameters']['trackAndTrace'] ?? null
            ],
            [
                "Name"              => "VATNumber",
                "Value"             => $this->payload['serviceParameters']['vatNumber'] ?? null
            ]
        ]);

        $this->request->getServices()->pushServiceList($serviceList);

        return $this->postRequest();
    }

    public function capture($payload): TransactionResponse
    {
        $this->payload = (new PayloadService($payload))->toArray();

        $capturePayload = (new CapturePayloadAdapter(new CapturePayload($this->payload)))->getValues();

        $this->request->setPayload($capturePayload);

        $serviceList = new ServiceList(
            self::PAYMENT_NAME,
            self::SERVICE_VERSION,
            'Capture'
        );

        $parametersService = new DefaultParameters($serviceList);
        $parametersService = new ArticleParameters($parametersService, $this->payload['serviceParameters']['articles'] ?? []);
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