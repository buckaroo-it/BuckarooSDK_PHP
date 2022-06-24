<?php

namespace Buckaroo\PaymentMethods\CreditManagement\Models;

use Buckaroo\Models\{Address, Company, Debtor, Email, Person, Phone, ServiceParameter};
use Buckaroo\PaymentMethods\CreditManagement\Service\ParameterKeys\ArticleAdapter;
use Buckaroo\PaymentMethods\Traits\CountableGroupKey;

class Invoice extends ServiceParameter
{
    use CountableGroupKey;

    private array $countableProperties = ['articles'];

    protected string $invoiceNumber;
    protected float $invoiceAmount;
    protected float $invoiceAmountVat;
    protected string $invoiceDate;
    protected string $dueDate;
    protected string $schemeKey;
    protected int $maxStepIndex;
    protected string $allowedServices;
    protected string $code;
    protected string $disallowedServices;
    protected string $allowedServicesAfterDueDate;
    protected string $disallowedServicesAfterDueDate;
    protected string $applyStartRecurrent;
    protected string $poNumber;
    protected array $articles = [];

    protected Address $address;
    protected Company $company;
    protected Person $person;
    protected Debtor $debtor;
    protected Email $email;
    protected Phone $phone;

    protected array $groupData = [
        'articles'   => [
            'groupType' => 'ProductLine'
        ],
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

    public function address($address = null)
    {
        if(is_array($address))
        {
            $this->address = new Address($address);
        }

        return $this->address;
    }

    public function company($company = null)
    {
        if(is_array($company))
        {
            $this->company = new Company($company);
        }

        return $this->company;
    }

    public function person($person = null)
    {
        if(is_array($person))
        {
            $this->person = new Person($person);
        }

        return $this->person;
    }

    public function debtor($debtor = null)
    {
        if(is_array($debtor))
        {
            $this->debtor = new Debtor($debtor);
        }

        return $this->debtor;
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

    public function articles(?array $articles = null)
    {
        if(is_array($articles))
        {
            foreach($articles as $article)
            {
                $this->articles[] = new ArticleAdapter(new Article($article));
            }
        }

        return $this->articles;
    }
}