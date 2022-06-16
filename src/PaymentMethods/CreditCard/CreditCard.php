<?php
namespace Buckaroo\PaymentMethods\CreditCard;

use Buckaroo\Models\CapturePayload;
use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\Transaction\Request\Adapters\CapturePayloadAdapter;
use Buckaroo\Transaction\Response\TransactionResponse;

class CreditCard extends PaymentMethod
{
    public function payEncrypted(): TransactionResponse
    {
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

    public function authorizeEncrypted(): TransactionResponse
    {
        $this->request->setPayload($this->getPaymentPayload());

        $serviceList =  $this->getServiceList('AuthorizeEncrypted');

        $serviceList->appendParameter([
            "Name"              => "EncryptedCardData",
            "GroupType"         => "",
            "GroupID"           => "",
            "Value"             => $this->payload['serviceParameters']['cardData'] ?? null
        ]);

        $this->request->getServices()->pushServiceList($serviceList);

        return $this->postRequest();
    }

    public function payWithSecurityCode(): TransactionResponse
    {
        $this->request->setPayload($this->getPaymentPayload());

        $serviceList =  $this->getServiceList('PayWithSecurityCode');

        $serviceList->appendParameter([
            "Name"              => "EncryptedSecurityCode",
            "Value"             => $this->payload['serviceParameters']['securityCode'] ?? null
        ]);

        $this->request->getServices()->pushServiceList($serviceList);

        return $this->postRequest();
    }

    public function authorizeWithSecurityCode(): TransactionResponse
    {
        $this->request->setPayload($this->getPaymentPayload());

        $serviceList =  $this->getServiceList('AuthorizeWithSecurityCode');

        $serviceList->appendParameter([
            "Name"              => "EncryptedSecurityCode",
            "Value"             => $this->payload['serviceParameters']['securityCode'] ?? null
        ]);

        $this->request->getServices()->pushServiceList($serviceList);

        return $this->postRequest();
    }

    public function authorize(): TransactionResponse
    {
        $this->request->setPayload($this->getPaymentPayload());

        $serviceList =  $this->getServiceList('Authorize');

        $this->request->getServices()->pushServiceList($serviceList);

        return $this->postRequest();
    }

    public function capture(): TransactionResponse
    {
        $capturePayload = (new CapturePayloadAdapter(new CapturePayload($this->payload)))->getValues();

        $this->request->setPayload($capturePayload);

        $serviceList =  $this->getServiceList('Capture');

        $this->request->getServices()->pushServiceList($serviceList);

        return $this->postRequest();
    }

    public function payRecurrent(): TransactionResponse
    {
        $capturePayload = (new CapturePayloadAdapter(new CapturePayload($this->payload)))->getValues();

        $this->request->setPayload($capturePayload);

        $serviceList =  $this->getServiceList('PayRecurrent');

        $this->request->getServices()->pushServiceList($serviceList);

        return $this->postRequest();
    }

    private function getServiceList(string $action = ''): ServiceList
    {
        return new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            $action
        );
    }

    public function paymentName(): string
    {
        if(isset($this->payload['serviceParameters']['name']))
        {
            return $this->payload['serviceParameters']['name'];
        }

        throw new \Exception('Missing creditcard name');
    }
}