<?php

namespace Buckaroo\PaymentMethods\CreditClick;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\CreditClick\Models\Pay;
use Buckaroo\PaymentMethods\CreditClick\Models\Refund;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class CreditClick extends PaymentMethod
{
    protected string $paymentName = 'creditclick';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }

    public function refund(?Model $model = null): TransactionResponse
    {
        return parent::refund(new Refund($this->payload));
    }

//    public function setPayServiceList(array $serviceParameters = [])
//    {
//        $serviceList =  new ServiceList(
//            $this->paymentName(),
//            $this->serviceVersion(),
//            'Pay'
//        );
//
//        $parametersService = new CustomerParameters(new DefaultParameters($serviceList), ['customer' => new CustomerKeysAdapter((new Person())->setProperties($serviceParameters['customer'] ?? []))]);
//        $parametersService->data();
//
//        $this->request->getServices()->pushServiceList($serviceList);
//
//        return $this;
//    }

//    public function setRefundServiceList(array $serviceParameters = [])
//    {
//        $serviceList =  new ServiceList(
//            $this->paymentName(),
//            $this->serviceVersion(),
//            'Refund'
//        );
//
//        $serviceList->appendParameter([
//            "Name"              => "refundreason",
//            "Value"             => $serviceParameters['reason']
//        ]);
//
//        $this->request->getServices()->pushServiceList($serviceList);
//
//        return $this;
//    }
}