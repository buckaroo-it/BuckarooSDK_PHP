<?php

namespace Buckaroo\PaymentMethods\In3\Models;

use Buckaroo\Models\Address;
use Buckaroo\Models\Company;
use Buckaroo\Models\Email;
use Buckaroo\Models\Person;
use Buckaroo\Models\Phone;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Billink\Models\Article;

class Pay extends ServiceParameter
{
    protected string $customerType;
    protected string $invoiceDate;

    protected Person $customer;
    protected Company $company;
    protected Address $address;
    protected Email $email;
    protected Phone $phone;

    protected array $articles = [];
    protected array $subtotals = [];

    protected array $groupData = [
        'articles'   => [
            'groupType' => 'ProductLine'
        ],
        'address'   => [
            'groupType' => 'Address'
        ],
        'customer'      => [
            'groupType' => 'Person'
        ],
        'phone'         => [
            'groupType' => 'Phone'
        ],
        'email'         => [
            'groupType' => 'Email'
        ]
    ];

    public function setProperties(?array $data)
    {
        foreach($data ?? array() as $property => $value)
        {
            if(in_array($property, ['company', 'customer', 'address', 'email', 'phone', 'articles', 'subtotals']))
            {
                $this->$property($value);

                continue;
            }

            $this->$property = $value;
        }

        return $this;
    }

    public function articles(?array $articles = null)
    {
        if(is_array($articles))
        {
            foreach($articles as $article)
            {
                $this->articles[] = new Article($article);
            }
        }

        return $this->articles;
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

    public function customer($customer = null)
    {
        if(is_array($customer))
        {
            return $this->customer(new Person($customer));
        }

        if($customer instanceof Person)
        {
            $this->customer = $customer;
        }

        return $this->customer;
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

    public function subtotals(?array $subtotals = null)
    {
        if(is_array($subtotals))
        {
            foreach($subtotals as $subtotal)
            {
                $this->subtotals[] = new Subtotal($subtotal);
            }
        }

        return $this->subtotals;
    }

    public function getGroupKey(string $key, ?int $keyCount = 0): ?int
    {
        if(in_array($key, ['articles', 'subtotals']) && is_numeric($keyCount))
        {
            return intval($keyCount) + 1;
        }

        return $this->groupData[$key]['groupKey'] ?? null;
    }
}