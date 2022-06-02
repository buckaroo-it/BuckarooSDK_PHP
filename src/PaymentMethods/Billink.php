<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\CapturePayload;
use Buckaroo\Model\Customer;
use Buckaroo\Model\ServiceList;
use Buckaroo\PaymentMethods\Traits\HasArticleAndCustomerParameters;
use Buckaroo\Services\ServiceListParameters\ArticleParameters;
use Buckaroo\Services\ServiceListParameters\CustomerParameters;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;
use Buckaroo\Transaction\Request\Adapters\CapturePayloadAdapter;
use Buckaroo\Transaction\Response\TransactionResponse;

class Billink extends PaymentMethod
{
    use HasArticleAndCustomerParameters;

    protected string $paymentName = 'billink';

    public function authorize(): TransactionResponse
    {
        $this->request->setPayload($this->getPaymentPayload());

        $serviceList = new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Authorize'
        );

        $parametersService = new DefaultParameters($serviceList);
        $parametersService = new ArticleParameters($parametersService, $this->articles($this->payload['serviceParameters']['articles'] ?? []));
        $parametersService = new CustomerParameters($parametersService, ['customer' => (new Customer())->setProperties($this->payload['serviceParameters']['customer'] ?? [])]);
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

    public function capture(): TransactionResponse
    {
        $capturePayload = (new CapturePayloadAdapter(new CapturePayload($this->payload)))->getValues();

        $this->request->setPayload($capturePayload);

        $serviceList = new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Capture'
        );

        $parametersService = new DefaultParameters($serviceList);
        $parametersService = new ArticleParameters($parametersService, $this->articles($this->payload['serviceParameters']['articles'] ?? []));
        $parametersService->data();

        $this->request->getServices()->pushServiceList($serviceList);

        return $this->postRequest();
    }
}