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

    public function debtor($debtor = null)
    {
        if(is_array($debtor))
        {
            $this->debtor = new Debtor($debtor);
        }

        return $this->debtor;
    }

    public function bankAccount($bankAccount = null)
    {
        if(is_array($bankAccount))
        {
            $this->bankAccount = new BankAccount($bankAccount);
        }

        return $this->bankAccount;
    }

    public function email($email = null)
    {
        if(is_string($email))
        {
            $this->email = new Email($email);
        }

        return $this->email;
    }

    public function phone($phone = null)
    {
        if(is_array($phone))
        {
            $this->phone = new Phone($phone);
        }

        return $this->phone;
    }

    public function address($address = null)
    {
        if(is_array($address))
        {
            $this->address = new Address($address);
        }

        return $this->address;
    }

    public function person($person = null)
    {
        if(is_array($person))
        {
            $this->person = new Person($person);
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