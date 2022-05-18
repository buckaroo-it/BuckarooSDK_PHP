<?php

namespace Buckaroo\Transaction\Request;

use Buckaroo\Helpers\Base;
use Buckaroo\Helpers\Validate;
use Buckaroo\Model\ClientIP;
use Buckaroo\Model\Payload;
use Buckaroo\Model\Services;

class TransactionRequest extends Request
{
    protected $Currency = 'EUR';
    protected $Services;

    public function __construct()
    {
        parent::__construct(null);

        $this->ClientIP = new ClientIP;
        $this->ClientUserAgent =  Base::getRemoteUserAgent();
    }

    public function setCurrency(string $currency): self
    {
        if (!Validate::isCurrency($currency))
        {
            throw new \Exception("Invalid currency " . $currency);
        }

        $this->Currency = $currency;

        return $this;
    }

    public function setPayload(Payload $payload)
    {
        $this->Invoice = $payload->invoice;
        $this->Order = $payload->order;
        $this->ReturnURL = $payload->returnURL;
        $this->ReturnURLCancel = $payload->returnURLCancel;
        $this->PushURL = $payload->pushURL;
        $this->AmountDebit = $payload->amountDebit;

        $this->setCurrency($payload->currency);

        return $this;
    }

    public function getServices() : Services
    {
        $this->Services = $this->Services ?? new Services;

        return $this->Services;
    }

    public function toArray(): array
    {
        return [
            'ClientIP'          => $this->ClientIP->toArray(),
            'ClientUserAgent'   => $this->ClientUserAgent,
            'Services'          => $this->Services->toArray(),
            'Invoice'           => $this->Invoice,
            'Order'             => $this->Order,
            'ReturnURL'         => $this->ReturnURL,
            'ReturnURLCancel'   => $this->ReturnURLCancel,
            'PushURL'           => $this->PushURL,
            'AmountDebit'       => $this->AmountDebit,
            'Currency'          => $this->Currency
        ];
    }
}
