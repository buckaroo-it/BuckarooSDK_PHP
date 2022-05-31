<?php
namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\CapturePayload;
use Buckaroo\Model\ServiceList;
use Buckaroo\Services\PayloadService;
use Buckaroo\Transaction\Request\Adapters\CapturePayloadAdapter;
use Buckaroo\Transaction\Response\TransactionResponse;

class CreditCard extends PaymentMethod
{
    public const SERVICE_VERSION = 0;

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

    public function authorizeEncrypted($payload): TransactionResponse
    {
        $this->payload = (new PayloadService($payload))->toArray();

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

    public function payWithSecurityCode($payload): TransactionResponse
    {
        $this->payload = (new PayloadService($payload))->toArray();

        $this->request->setPayload($this->getPaymentPayload());

        $serviceList =  $this->getServiceList('PayWithSecurityCode');

        $serviceList->appendParameter([
            "Name"              => "EncryptedSecurityCode",
            "Value"             => $this->payload['serviceParameters']['securityCode'] ?? null
        ]);

        $this->request->getServices()->pushServiceList($serviceList);

        return $this->postRequest();
    }

    public function authorizeWithSecurityCode($payload): TransactionResponse
    {
        $this->payload = (new PayloadService($payload))->toArray();

        $this->request->setPayload($this->getPaymentPayload());

        $serviceList =  $this->getServiceList('AuthorizeWithSecurityCode');

        $serviceList->appendParameter([
            "Name"              => "EncryptedSecurityCode",
            "Value"             => $this->payload['serviceParameters']['securityCode'] ?? null
        ]);

        $this->request->getServices()->pushServiceList($serviceList);

        return $this->postRequest();
    }

    public function authorize($payload): TransactionResponse
    {
        $this->payload = (new PayloadService($payload))->toArray();

        $this->request->setPayload($this->getPaymentPayload());

        $serviceList =  $this->getServiceList('Authorize');

        $this->request->getServices()->pushServiceList($serviceList);

        return $this->postRequest();
    }

    public function capture($payload): TransactionResponse
    {
        $this->payload = (new PayloadService($payload))->toArray();

        $capturePayload = (new CapturePayloadAdapter(new CapturePayload($this->payload)))->getValues();

        $this->request->setPayload($capturePayload);

        $serviceList =  $this->getServiceList('Capture');

        $this->request->getServices()->pushServiceList($serviceList);

        return $this->postRequest();
    }

    public function payRecurrent($payload): TransactionResponse
    {
        $this->payload = (new PayloadService($payload))->toArray();

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

    public function serviceVersion(): int
    {
        return self::SERVICE_VERSION;
    }
}
