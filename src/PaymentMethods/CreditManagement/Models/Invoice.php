<?php

namespace Buckaroo\PaymentMethods\CreditManagement\Models;

use Buckaroo\Models\{Address, Company, Debtor, Email, Person, Phone, ServiceParameter};

class Invoice extends ServiceParameter
{
    protected string $invoiceNumber;
    protected float $invoiceAmount;
    protected float $invoiceAmountVat;
    protected string $invoiceDate;
    protected string $dueDate;
    protected string $schemeKey;
    protected int $maxStepIndex;
    protected string $allowedServices;
    protected string $disallowedServices;
    protected string $allowedServicesAfterDueDate;
    protected string $disallowedServicesAfterDueDate;
    protected string $applyStartRecurrent;

    protected Address $address;
    protected Company $company;
    protected Person $person;
    protected Debtor $debtor;
    protected Email $email;
    protected Phone $phone;

    protected array $groupData = [
        'address'   => [
            'groupType' => 'Address'
        ],
        'company'   => [
            'groupType' => 'Company'
        ],
        'person'   => [
            'groupType' => 'Person'
        ],
        'debtor'   => [
            'groupType' => 'Debtor'
        ],
        'email'   => [
            'groupType' => 'Email'
        ],
        'phone'   => [
            'groupType' => 'Phone'
        ],
    ];

    public function setProperties(?array $data)
    {
        foreach($data ?? array() as $property => $value)
        {
            if(in_array($property, ['address', 'company', 'person', 'debtor', 'email', 'phone']))
            {
                $this->$property($value);

                continue;
            }

            $this->$property = $value;
        }

        return $this;
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

    public function company($company = null)
    {
        if(is_array($company))
        {
            return $this->company(new Company($company));
        }

        if($company instanceof Company)
        {
            $this->company = $company;
        }

        return $this->company;
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
}