<?php

declare(strict_types=1);

namespace Buckaroo\Model;

class Customer extends Model
{
    protected Address $billing;
    protected Address $shipping;
    protected Address $address;
    protected bool $useBillingInfoForShipping;

    protected
        $gender,
        $initials,
        $firstName,
        $lastName,
        $birthDate,
        $email,
        $phone;

    public function setProperties(array $data)
    {
        foreach($data ?? array() as $property => $value)
        {
            if(in_array($property, ['billing', 'shipping', 'address']))
            {
                $this->$property = (new Address())->setProperties($value);

                continue;
            }

            $this->$property = $value;
        }

        $this->isUseingBillingInfoForShipping();

        return $this;
    }

    private function isUseingBillingInfoForShipping(): Customer
    {
        if(isset($this->useBillingInfoForShipping)) {
            $this->shipping = ($this->useBillingInfoForShipping)? clone $this->billing : $this->shipping;
        }

        return $this;
    }
}
