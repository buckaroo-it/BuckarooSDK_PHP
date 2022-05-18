<?php

namespace Buckaroo\Transaction\Request;

use Buckaroo\Helpers\Arrayable;
use Buckaroo\Helpers\Base;
use Buckaroo\Model\ClientIP;
use Buckaroo\Model\Services;
use Buckaroo\Transaction\Request\Adapters\TransactionAdapter;

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

    public function setPayload(TransactionAdapter $adapter)
    {
        foreach($adapter->getValues() as $property => $value)
        {
            $this->data[$property] = $value;
        }

        return $this;
    }

    public function getServices() : Services
    {
        $this->data['Services'] = $this->data['Services'] ?? new Services;

        return $this->data['Services'];
    }

    public function toArray(): array
    {
        foreach($this->data as $key => $value)
        {
            if(is_a($value, Arrayable::class))
            {
                $this->data[$key] = $value->toArray();
            }
        }

        return $this->data;
    }
}
