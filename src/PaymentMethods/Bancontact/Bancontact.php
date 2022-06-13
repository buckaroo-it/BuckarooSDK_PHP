<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\Bancontact;

use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\Services\PayloadService;
use Buckaroo\Transaction\Response\TransactionResponse;

class Bancontact extends PaymentMethod
{
    protected string $paymentName = 'bancontactmrcash';
    protected int $serviceVersion = 1;

    public function payEncrypted($payload): TransactionResponse
    {
        $this->payload = (new PayloadService($payload))->toArray();

        $this->request->setPayload($this->getPaymentPayload());

        $serviceList =  $this->getServiceList('PayEncrypted');

        $serviceList->appendParameter([
            "Name"              => "EncryptedCardData",
            "GroupType"         => "",
            "GroupID"           => "",
            "Value"             => $this->payload['serviceParameters']['cardData'] ?? null
        ]);

        $this->request->getServices()->pushServiceList($serviceList);

        return $this->postRequest();
    }
    
    public function setPayServiceList(array $serviceParameters = []): self
    {
        $serviceList = new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay'
        );

        if (isset($serviceParameters['saveToken'])) {
                $serviceList->appendParameter(
                    [
                        "Name"              => "SaveToken",
                        "Value"             => $serviceParameters['saveToken'],
                        "GroupType"         => "",
                        "GroupID"           => ""
                    ]
                );
        }


        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }

    private function getServiceList(string $action = ''): ServiceList
    {
        return new ServiceList(
            $this->paymentName(),
            0,
            $action
        );
    }
}