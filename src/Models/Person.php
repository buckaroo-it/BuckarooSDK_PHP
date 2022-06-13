<?php

declare(strict_types=1);

namespace Buckaroo\Models;

class Person extends Model
{
//    protected Address $billing;
//    protected Address $shipping;
//    protected Address $address;
//    protected bool $useBillingInfoForShipping;

    protected int $gender;
    protected string $culture;
    protected string $title;
    protected string $initials;
    protected string $name;
    protected string $firstName;
    protected string $lastNamePrefix;
    protected string $lastName;
    protected string $birthDate;
    protected string $placeOfBirth;
    protected string $email;
    protected string $phone;
    protected string $country;

//    public function setProperties(array $data)
//    {
//        foreach($data ?? array() as $property => $value)
//        {
//            if(in_array($property, ['billing', 'shipping', 'address']))
//            {
//                $this->$property = (new Address())->setProperties($value);
//
//                continue;
//            }
//
//            $this->$property = $value;
//        }
//
//        $this->isUseingBillingInfoForShipping();
//
//        return $this;
//    }
//
//    private function isUseingBillingInfoForShipping(): Person
//    {
//        if(isset($this->useBillingInfoForShipping)) {
//            $this->shipping = ($this->useBillingInfoForShipping)? clone $this->billing : $this->shipping;
//        }
//
//        return $this;
//    }
}
