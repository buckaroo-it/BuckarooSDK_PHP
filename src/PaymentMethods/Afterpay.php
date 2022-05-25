<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Client;
use Buckaroo\Model\Address;
use Buckaroo\Model\Article;
use Buckaroo\Model\CapturePayload;
use Buckaroo\Model\Customer;
use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\RefundPayload;
use Buckaroo\Model\ServiceList;
use Buckaroo\Services\AfterpayParametersService;
use Buckaroo\Services\PayloadService;
use Buckaroo\Transaction\Request\Adapters\CapturePayloadAdapter;
use Buckaroo\Transaction\Response\TransactionResponse;

class Afterpay extends PaymentMethod
{
    public const SERVICE_VERSION = 1;

    private AfterpayParametersService $parametersService;

    public function __construct(Client $client)
    {
        $this->parametersService = new AfterpayParametersService();

        parent::__construct($client);
    }

    public function authorize($payload): TransactionResponse
    {
        $this->payload = (new PayloadService($payload))->toArray();

        $this->request->setPayload($this->getPaymentPayload());

        $this->parametersService->processArticles($this->payload['serviceParameters']['articles'] ?? []);
        $this->parametersService->processCustomer($this->payload['serviceParameters']['customer'] ?? []);

        $serviceList = new ServiceList(
            self::AFTERPAY,
            self::SERVICE_VERSION,
            'Authorize',
            $this->parametersService->toArray()
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this->postRequest();
    }

    public function capture($payload): TransactionResponse
    {
        $this->payload = (new PayloadService($payload))->toArray();

        $capturePayload = (new CapturePayloadAdapter(new CapturePayload($this->payload)))->getValues();

        $this->request->setPayload($capturePayload);

        $this->parametersService->processArticles($this->payload['serviceParameters']['articles'] ?? []);

        $serviceList = new ServiceList(
            self::AFTERPAY,
            self::SERVICE_VERSION,
            'Capture',
            $this->parametersService->toArray()
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this->postRequest();
    }

    public function setPayServiceList(array $serviceParameters = [])
    {
        $this->parametersService->processArticles($serviceParameters['articles'] ?? []);
        $this->parametersService->processCustomer($serviceParameters['customer'] ?? []);

        $serviceList =  new ServiceList(
            self::AFTERPAY,
            self::SERVICE_VERSION,
            'Pay',
            $this->parametersService->toArray()
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }

    public function paymentName(): string
    {
        return self::AFTERPAY;
    }

    public function serviceVersion(): int
    {
        return self::SERVICE_VERSION;
    }
}
