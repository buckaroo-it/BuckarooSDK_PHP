<?php

namespace Buckaroo\PaymentMethods\Subscriptions\Models;

use Buckaroo\Models\Address;
use Buckaroo\Models\BankAccount;
use Buckaroo\Models\Debtor;
use Buckaroo\Models\Email;
use Buckaroo\Models\Phone;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Afterpay\Models\Person;

class Subscription extends ServiceParameter
{
    protected bool $includeTransaction;
    protected float $transactionVatPercentage;
    protected string $configurationCode;
    protected string $subscriptionGuid;
    protected int $termStartDay;
    protected int $termStartMonth;
    protected int $billingTiming;

    protected string $termStartWeek;
    protected string $b2b;
    protected string $mandateReference;
    protected string $allowedServices;

    protected Debtor $debtor;
    protected BankAccount $bankAccount;
    protected Email $email;
    protected Phone $phone;
    protected Address $address;
    protected Person $person;

    protected RatePlan $addRatePlan;
    protected RatePlan $updateRatePlan;
    protected RatePlan $disableRatePlan;

    protected array $groupData = [
        'debtor'   => [
            'groupType' => 'Debtor'
        ],
        'addRatePlan'   => [
            'groupType' => 'AddRatePlan'
        ],
        'updateRatePlan'   => [
            'groupType' => 'UpdateRatePlan'
        ],
        'disableRatePlan'   => [
            'groupType' => 'DisableRatePlan'
        ],
    ];

    public function setProperties(?array $data)
    {
        foreach($data ?? array() as $property => $value)
        {
            if(in_array($property, ['debtor', 'bankAccount', 'email', 'phone', 'address', 'person', 'rate_plans']))
            {
                $this->$property($value);

                continue;
            }

            $this->$property = $value;
        }

        return $this;
    }

    public function debtor($debtor = null)
    {
        if(is_array($debtor))
        {
            return $this->debtor(new Debtor($debtor));
        }

        if($debtor instanceof Debtor)
        {
            $this->debtor = $debtor;
        }

        return $this->debtor;
    }

    public function bankAccount($bankAccount = null)
    {
        if(is_array($bankAccount))
        {
            return $this->bankAccount(new BankAccount($bankAccount));
        }

        if($bankAccount instanceof BankAccount)
        {
            $this->bankAccount = $bankAccount;
        }

        return $this->bankAccount;
    }

    public function email($email = null)
    {
        if(is_string($email))
        {
            return $this->email(new Email($email));
        }

        if($email instanceof Email)
        {
            $this->email = $email;
        }

        return $this->email;
    }

    public function phone($phone = null)
    {
        if(is_array($phone))
        {
            return $this->phone(new Phone($phone));
        }

        if($phone instanceof Phone)
        {
            $this->phone = $phone;
        }

        return $this->phone;
    }

    public function address($address = null)
    {
        if(is_array($address))
        {
            return $this->address(new Address($address));
        }

        if($address instanceof Address)
        {
            $this->address = $address;
        }

        return $this->address;
    }

    public function person($person = null)
    {
        if(is_array($person))
        {
            return $this->person(new Person($person));
        }

        if($person instanceof Person)
        {
            $this->person = $person;
        }

        return $this->person;
    }

    public function rate_plans($rate_plans = null)
    {
        if(is_array($rate_plans))
        {
            foreach($rate_plans as $type => $rate_plan)
            {
                $property = $type . 'RatePlan';

                $this->$property = new RatePlan(ucfirst($type), $rate_plan);
            }
        }


        return $this;
    }
}