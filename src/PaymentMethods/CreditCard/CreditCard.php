<?php
namespace Buckaroo\PaymentMethods\CreditCard;

use Buckaroo\PaymentMethods\CreditCard\Models\CardData;
use Buckaroo\PaymentMethods\CreditCard\Models\SecurityCode;
use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class CreditCard extends PayablePaymentMethod implements Combinable
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
