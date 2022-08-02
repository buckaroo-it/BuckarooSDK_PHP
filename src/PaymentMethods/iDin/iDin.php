<?php

namespace Buckaroo\PaymentMethods\iDin;

use Buckaroo\Models\Payload\PayPayload;
use Buckaroo\PaymentMethods\iDin\Models\Issuer;
use Buckaroo\PaymentMethods\iDin\Service\ParameterKeys\IssuerAdapter;
use Buckaroo\PaymentMethods\PaymentMethod;

class iDin extends PaymentMethod
{
    protected string $paymentName = 'Idin';
    protected array $requiredConfigFields = ['returnURL', 'returnURLCancel', 'returnURLError', 'returnURLReject'];

    public function identify()
    {
        $issuer = new IssuerAdapter(new Issuer($this->payload));

        $payPayload = new PayPayload($this->payload);

        $this->request->setPayload($payPayload);

        $this->setServiceList('identify', $issuer);

        return $this->dataRequest();
    }

    public function verify()
    {
        $issuer = new IssuerAdapter(new Issuer($this->payload));

        $payPayload = new PayPayload($this->payload);

        $this->request->setPayload($payPayload);

        $this->setServiceList('verify', $issuer);

        return $this->dataRequest();
    }

    public function login()
    {
        $issuer = new IssuerAdapter(new Issuer($this->payload));

        $payPayload = new PayPayload($this->payload);

        $this->request->setPayload($payPayload);

        $this->setServiceList('login', $issuer);

        return $this->dataRequest();
    }

}