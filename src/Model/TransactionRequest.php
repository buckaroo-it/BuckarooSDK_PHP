<?php

namespace Buckaroo\Model;

use Buckaroo\Helpers\Base;
use Buckaroo\Helpers\Validate;

class TransactionRequest extends Model
{
    protected $fillable = [
        'ClientIP',
        'ClientUserAgent',
        'Services',
        'Currency',
        'Invoice',
        'Order',
        'ReturnURL',
        'ReturnURLCancel',
        'PushURL',
        'AmountDebit'
    ];

    protected $Currency = 'EUR';

    public function __construct()
    {
        $this->ClientIP = new ClientIP();
        $this->ClientUserAgent =  Base::getRemoteUserAgent();
        $this->Services = new Services();
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
        $this->ReturnURL = $payload->returnURL;
        $this->ReturnURLCancel = $payload->returnURLCancel;
        $this->PushURL = $payload->pushURL;
        $this->AmountDebit = $payload->amountDebit;

        $this->setCurrency($payload->currency);

        return $this;
    }

    public function getServices() : Services
    {
        return $this->Services;
    }
}