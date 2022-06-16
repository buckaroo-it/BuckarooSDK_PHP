<?php

namespace Buckaroo\PaymentMethods\Afterpay;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\Afterpay\Models\Pay;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\PaymentMethods\Traits\HasArticleAndCustomerParameters;
use Buckaroo\Transaction\Response\TransactionResponse;

class Afterpay extends PaymentMethod
{
    use HasArticleAndCustomerParameters;

    protected string $paymentName = 'afterpay';
    protected int $serviceVersion = 1;

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }

//    public function authorize(): TransactionResponse
//    {
//        $this->request->setPayload($this->getPaymentPayload());
//
//        $serviceList = new ServiceList(
//            $this->paymentName(),
//            $this->serviceVersion(),
//            'Authorize'
//        );
//
//        $parametersService = new ArticleParameters(new DefaultParameters($serviceList),  $this->articles($this->payload['serviceParameters']['articles'] ?? []));
//        $parametersService = new CustomerParameters($parametersService, ['customer' => (new Person())->setProperties($this->payload['serviceParameters']['customer'] ?? [])]);
//        $parametersService->data();
//
//        $this->request->getServices()->pushServiceList($serviceList);
//
//        return $this->postRequest();
//    }
//
//    public function capture(): TransactionResponse
//    {
//        $capturePayload = (new CapturePayloadAdapter(new CapturePayload($this->payload)))->getValues();
//
//        $this->request->setPayload($capturePayload);
//
//        $serviceList = new ServiceList(
//            $this->paymentName(),
//            $this->serviceVersion(),
//            'Capture'
//        );
//
//        $parametersService = new ArticleParameters(new DefaultParameters($serviceList), $this->articles($this->payload['serviceParameters']['articles'] ?? []));
//        $parametersService->data();
//
//        $this->request->getServices()->pushServiceList($serviceList);
//
//        return $this->postRequest();
//    }
}
