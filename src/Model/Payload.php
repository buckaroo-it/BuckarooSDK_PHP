<?php
declare(strict_types=1);

namespace Buckaroo\Model;

class Payload extends Model
{
    protected $fillable = [
        'amountDebit',
        'serviceVersion',
        'serviceAction',
        'invoice',
        'issuer',
        'method',
        'order',
        'currency',
        'returnURL',
        'returnURLCancel',
        'pushURL'
    ];

    protected $serviceVersion = 2;
    protected $serviceAction = 'Pay';
    protected $invoice = null;
    protected $order = null;
    protected $currency = null;
    protected $returnURL = null;
    protected $returnURLCancel = null;
    protected $pushURL = null;

    public function __construct(?array $payload)
    {
        $this->setDefaultPayload();
        $this->setPayload($payload);
    }

    private function setPayload(?array $payload)
    {
        if($payload)
        {
            foreach($payload ?? array() as $property => $value)
            {
                $this->$property = $value;
            }
        }
    }

    private function setDefaultPayload()
    {
        $this->invoice = uniqid($_ENV['BPE_WEBSITE'] . '_INVOICE_NO_');
        $this->order = uniqid($_ENV['BPE_WEBSITE'] . '_ORDER_NO_');
        $this->currency = $_ENV['BPE_EXAMPLE_CURRENCY_CODE'];
        $this->returnURL = $_ENV['BPE_EXAMPLE_RETURN_URL'];
        $this->returnURLCancel = $_ENV['BPE_EXAMPLE_RETURN_URL'];
        $this->pushURL = $_ENV['BPE_EXAMPLE_RETURN_URL'];
    }
}