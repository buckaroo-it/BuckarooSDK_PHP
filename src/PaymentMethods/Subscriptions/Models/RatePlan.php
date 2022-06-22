<?php

namespace Buckaroo\PaymentMethods\Subscriptions\Models;

use Buckaroo\Models\ServiceParameter;

class RatePlan extends ServiceParameter
{
    private string $type;

    protected string $ratePlanGuid;
    protected string $ratePlanCode;
    protected string $startDate;
    protected string $endDate;

    protected Charge $charge;

    public function __construct(string $type, ?array $values = null)
    {
        $this->type = $type;

        parent::__construct($values);
    }

    public function setProperties(?array $data)
    {
        foreach($data ?? array() as $property => $value)
        {
            if(in_array($property, ['charge']))
            {
                $this->$property($value);

                continue;
            }

            $this->$property = $value;
        }

        return $this;
    }

    public function charge($charge = null)
    {
        if(is_array($charge))
        {
            return $this->charge(new Charge($charge));
        }

        if($charge instanceof Charge)
        {
            $this->charge = $charge;
        }

        return $this->charge;
    }

    public function getGroupType(string $key): ?string
    {
        if($key == 'charge')
        {
            return $this->type . 'RatePlanCharge';
        }

        return parent::getGroupKey($key);
    }
}