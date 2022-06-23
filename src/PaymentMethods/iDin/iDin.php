<?php

namespace Buckaroo\PaymentMethods\iDin;

use Buckaroo\Models\PayPayload;
use Buckaroo\PaymentMethods\iDin\Adapters\IssuerServiceParametersKeysAdapter;
use Buckaroo\PaymentMethods\iDin\Models\Issuer;
use Buckaroo\PaymentMethods\PaymentMethod;

class iDin extends PaymentMethod
{
    protected string $paymentName = 'Idin';
    protected array $requiredConfigFields = ['returnURL', 'returnURLCancel', 'returnURLError', 'returnURLReject'];

    public function identify()
    {
        $issuer = new IssuerServiceParametersKeysAdapter(new Issuer($this->payload));

        $payPayload = new PayPayload($this->payload);

        $this->request->setPayload($payPayload);

        $this->setServiceList('identify', $issuer);

        return $this->dataRequest();
    }

    public function verify()
    {
        $issuer = new IssuerServiceParametersKeysAdapter(new Issuer($this->payload));

        $payPayload = new PayPayload($this->payload);

        $this->request->setPayload($payPayload);

        $this->setServiceList('verify', $issuer);

        return $this->dataRequest();
    }

    public function login()
    {
        $issuer = new IssuerServiceParametersKeysAdapter(new Issuer($this->payload));

        $payPayload = new PayPayload($this->payload);

        $this->request->setPayload($payPayload);

        $this->setServiceList('login', $issuer);

        return $this->dataRequest();
    }

}