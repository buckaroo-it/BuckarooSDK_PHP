<?php
namespace Buckaroo\PaymentMethods\CreditCard;

use Buckaroo\Models\CapturePayload;
use Buckaroo\Models\Model;
use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\Billink\Models\Pay;
use Buckaroo\PaymentMethods\CreditCard\Models\CardData;
use Buckaroo\PaymentMethods\CreditCard\Models\SecurityCode;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\Transaction\Request\Adapters\CapturePayloadAdapter;
use Buckaroo\Transaction\Response\TransactionResponse;

class CreditCard extends PaymentMethod
{
    public function payEncrypted(): TransactionResponse
    {
        $cardData = new CardData($this->payload);

        $this->setPayPayload();

        $this->setServiceList('PayEncrypted', $cardData);

        return $this->postRequest();
    }

    public function authorizeEncrypted(): TransactionResponse
    {
        $cardData = new CardData($this->payload);

        $this->setPayPayload();

        $this->setServiceList('AuthorizeEncrypted', $cardData);

        return $this->postRequest();
    }

    public function payWithSecurityCode(): TransactionResponse
    {
        $securityCode = new SecurityCode($this->payload);

        $this->setPayPayload();

        $this->setServiceList('PayWithSecurityCode', $securityCode);

        return $this->postRequest();
    }

    public function authorizeWithSecurityCode(): TransactionResponse
    {
        $securityCode = new SecurityCode($this->payload);

        $this->setPayPayload();

        $this->setServiceList('AuthorizeWithSecurityCode', $securityCode);

        return $this->postRequest();
    }

    public function authorize(): TransactionResponse
    {
        $this->setPayPayload();

        $this->setServiceList('Authorize');

        return $this->postRequest();
    }

    public function capture(): TransactionResponse
    {
        $this->setPayPayload();

        $this->setServiceList('Capture');

        return $this->postRequest();
    }

    public function payRecurrent(): TransactionResponse
    {
        $this->setPayPayload();

        $this->setServiceList('PayRecurrent');

        return $this->postRequest();
    }

    public function paymentName(): string
    {
        if(isset($this->payload['name']))
        {
            return $this->payload['name'];
        }

        throw new \Exception('Missing creditcard name');
    }
}
